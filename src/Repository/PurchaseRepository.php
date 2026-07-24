<?php

declare(strict_types=1);

namespace App\Repository;

use App\Domain\ClickBankPurchaseStatus;
use App\Domain\InnerCircleSkus;
use App\Domain\ReadingProductSkus;
use JsonException;
use PDO;

final class PurchaseRepository
{
    public function __construct(private readonly PDO $pdo) {}

    public function findIdByReceipt(string $receipt): ?int
    {
        $stmt = $this->pdo->prepare('SELECT id FROM purchases WHERE clickbank_receipt = :receipt LIMIT 1');
        $stmt->execute([':receipt' => $receipt]);
        $id = $stmt->fetchColumn();

        return is_numeric($id) ? (int) $id : null;
    }

    /**
     * First approved main-reading purchase for this lead that has no completed PDF delivery yet.
     */
    public function findDeliverableMainReadingPurchaseId(int $leadId): ?int
    {
        $stmt = $this->pdo->prepare(
            "SELECT p.id, p.items_json
             FROM purchases p
             LEFT JOIN reading_deliveries d ON d.purchase_id = p.id AND d.status IN ('generated', 'emailed')
             WHERE p.lead_id = :lead_id
               AND d.id IS NULL
               AND p.status IN ('approved', 'complete', 'completed', 'active')
             ORDER BY p.created_at ASC"
        );
        $stmt->execute([':lead_id' => $leadId]);

        while (($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {
            $raw = $row['items_json'] ?? '';
            if (!is_string($raw) || $raw === '') {
                continue;
            }
            try {
                $items = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
            } catch (\JsonException) {
                continue;
            }
            if (!is_array($items)) {
                continue;
            }
            if (ReadingProductSkus::purchaseIncludesMainReading($items)) {
                return (int) $row['id'];
            }
        }

        return null;
    }

    public function buyerHasAnyPurchase(int $leadId): bool
    {
        $stmt = $this->pdo->prepare(
            "SELECT id FROM purchases
             WHERE lead_id = :lead_id
               AND status IN ('approved', 'complete', 'completed', 'active')
               AND (access_until IS NULL OR access_until > CURRENT_TIMESTAMP)
             LIMIT 1"
        );
        $stmt->execute([':lead_id' => $leadId]);

        return $stmt->fetchColumn() !== false;
    }

    /**
     * True when any non-refunded purchase row for this lead includes the given item id
     * (ClickBank line item keys: sku, item, itemNo, etc.).
     */
    public function leadHasApprovedPurchaseWithItemSku(int $leadId, string $sku): bool
    {
        $needle = strtolower(trim($sku));
        if ($needle === '') {
            return false;
        }

        $stmt = $this->pdo->prepare(
            "SELECT items_json FROM purchases
             WHERE lead_id = :lead_id
               AND status IN ('approved', 'complete', 'completed', 'active')
               AND (access_until IS NULL OR access_until > CURRENT_TIMESTAMP)"
        );
        $stmt->execute([':lead_id' => $leadId]);

        while (($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {
            $raw = $row['items_json'] ?? '';
            if (!is_string($raw) || $raw === '') {
                continue;
            }
            try {
                $decoded = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
            } catch (JsonException) {
                continue;
            }
            if (!is_array($decoded)) {
                continue;
            }
            foreach ($decoded as $item) {
                if (!is_array($item)) {
                    continue;
                }
                foreach (['sku', 'item', 'itemNo', 'productSku'] as $key) {
                    if (!array_key_exists($key, $item)) {
                        continue;
                    }
                    $val = $item[$key];
                    if (is_string($val) && strtolower(trim($val)) === $needle) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    public function leadHasApprovedInnerCirclePurchase(int $leadId): bool
    {
        foreach (InnerCircleSkus::ALL as $sku) {
            if ($this->leadHasApprovedPurchaseWithItemSku($leadId, $sku)) {
                return true;
            }
        }

        return false;
    }

    /**
     * The paid-through timestamp (max access_until) across this lead's approved and cancelled
     * Inner Circle rows, or null when none carry a soft-cancel window. Cancelled rows are included
     * because a FIRST-MONTH cancel stamps its paid-through window on the just-cancelled row (the
     * INS upsert flips the buyer's only row before revocation runs, so no approved row remains).
     * Used for logging and Slack display only; never for entitlement checks.
     */
    public function innerCircleAccessUntil(int $leadId): ?string
    {
        $stmt = $this->pdo->prepare(
            "SELECT items_json, access_until FROM purchases
             WHERE lead_id = :lead_id
               AND status IN ('approved', 'complete', 'completed', 'active', 'cancelled')
               AND access_until IS NOT NULL"
        );
        $stmt->execute([':lead_id' => $leadId]);

        $latest = null;
        while (($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {
            $raw = $row['items_json'] ?? '';
            $accessUntil = $row['access_until'] ?? null;
            if (!is_string($raw) || $raw === '' || !is_string($accessUntil) || $accessUntil === '') {
                continue;
            }
            try {
                $items = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
            } catch (JsonException) {
                continue;
            }
            if (!is_array($items) || !$this->purchaseContainsAnySku($items, InnerCircleSkus::ALL)) {
                continue;
            }
            if ($latest === null || $accessUntil > $latest) {
                $latest = $accessUntil;
            }
        }

        return $latest;
    }

    /**
     * @param list<non-empty-string> $skus
     */
    public function purchaseContainsAnySku(array $items, array $skus): bool
    {
        $needles = array_map(static fn (string $s): string => strtolower(trim($s)), $skus);
        foreach ($items as $item) {
            if (!is_array($item)) {
                continue;
            }
            foreach (['sku', 'item', 'itemNo', 'productSku'] as $key) {
                if (!array_key_exists($key, $item)) {
                    continue;
                }
                $val = $item[$key];
                if (!is_string($val) && !is_numeric($val)) {
                    continue;
                }
                $needle = strtolower(trim((string) $val));
                if ($needle !== '' && in_array($needle, $needles, true)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Marks approved purchases containing any of the given SKUs as revoked and clears
     * access_until so a prior soft-cancel window cannot outlive a refund/chargeback.
     *
     * @param list<non-empty-string> $skus
     */
    public function revokeApprovedPurchasesContainingSkus(
        int $leadId,
        array $skus,
        string $revokedStatus,
    ): int {
        if ($skus === []) {
            return 0;
        }

        $stmt = $this->pdo->prepare(
            "SELECT id, items_json FROM purchases
             WHERE lead_id = :lead_id
               AND status IN ('approved', 'complete', 'completed', 'active')"
        );
        $stmt->execute([':lead_id' => $leadId]);

        $update = $this->pdo->prepare(
            'UPDATE purchases
             SET status = :status, access_until = NULL, updated_at = CURRENT_TIMESTAMP
             WHERE id = :id'
        );

        $revoked = 0;
        while (($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {
            $raw = $row['items_json'] ?? '';
            if (!is_string($raw) || $raw === '') {
                continue;
            }
            try {
                $items = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
            } catch (JsonException) {
                continue;
            }
            if (!is_array($items) || !$this->purchaseContainsAnySku($items, $skus)) {
                continue;
            }

            $update->execute([
                ':status' => $revokedStatus,
                ':id' => (int) $row['id'],
            ]);
            ++$revoked;
        }

        return $revoked;
    }

    /**
     * Soft-cancels Inner Circle access: keeps approved ic-1 / ic-1-ds rows approved but
     * stamps access_until so the buyer keeps access until the paid period ends.
     *
     * access_until = created_at of the MOST RECENT approved SALE / BILL / TEST_SALE / TEST_BILL
     * purchase row containing one of $skus, plus one month, floored to CURRENT_TIMESTAMP so a
     * past-dated result never revokes retroactively. The date math is computed DB-side so it is
     * timezone-safe, mirroring purchaseUnlockSecondsRemaining().
     *
     * FIRST-MONTH CANCEL fallback: the INS handler upserts the event BEFORE revocation runs, so
     * when the buyer's only Inner Circle row shares the cancel event's receipt it is already
     * status = cancelled here and the approved scan finds nothing. In that case the same window is
     * derived from (and stamped on) the cancelled rows themselves, so the paid-through date still
     * anchors on the last billing row's created_at. A window on a cancelled row never grants
     * PHP-side entitlement (those queries require an approved status); it feeds the Worker
     * notification and the audit log. Refunded / chargeback rows are NEVER used: those reverse
     * the payment and grant nothing.
     *
     * @param list<non-empty-string> $skus
     * @return string|null The access_until value that was written (as stored by the driver), or
     *                      null when no approved or cancelled matching row exists to update.
     */
    public function setInnerCircleAccessUntil(int $leadId, array $skus): ?string
    {
        if ($skus === []) {
            return null;
        }

        // Approved rows that grant the entitlement (these get access_until stamped) and, among
        // them, the billing rows (SALE / BILL / TEST_SALE / TEST_BILL) that anchor the paid period.
        [$entitlementIds, $anchorIds] = $this->innerCircleWindowRows($leadId, $skus, ClickBankPurchaseStatus::APPROVED);

        // First-month cancel: no approved row left, fall back to the just-cancelled rows.
        if ($entitlementIds === []) {
            [$entitlementIds, $anchorIds] = $this->innerCircleWindowRows($leadId, $skus, ['cancelled']);
        }

        if ($entitlementIds === []) {
            return null;
        }

        // Anchor on the billing rows when present; otherwise fall back to the entitlement rows so
        // an approved-but-untyped row (or a cancelled row whose txn_type was overwritten by the
        // cancel upsert) still yields a sensible paid-through date.
        $anchorIds = $anchorIds !== [] ? $anchorIds : $entitlementIds;

        $anchorPlaceholders = implode(', ', array_fill(0, count($anchorIds), '?'));
        $accessUntil = $this->computeAccessUntil($anchorPlaceholders, $anchorIds);
        if ($accessUntil === null) {
            return null;
        }

        $entPlaceholders = implode(', ', array_fill(0, count($entitlementIds), '?'));
        $update = $this->pdo->prepare(
            "UPDATE purchases
             SET access_until = ?, updated_at = CURRENT_TIMESTAMP
             WHERE id IN ($entPlaceholders)"
        );
        $update->execute(array_merge([$accessUntil], $entitlementIds));

        return $accessUntil;
    }

    /**
     * Purchase ids for this lead in the given statuses whose items include one of $skus
     * (entitlement rows), plus the subset whose txn_type is a billing type (anchor rows).
     *
     * @param list<non-empty-string> $skus
     * @param list<non-empty-string> $statuses
     * @return array{0: list<int>, 1: list<int>} [entitlementIds, anchorIds]
     */
    private function innerCircleWindowRows(int $leadId, array $skus, array $statuses): array
    {
        $statusPlaceholders = implode(', ', array_fill(0, count($statuses), '?'));
        $stmt = $this->pdo->prepare(
            "SELECT id, txn_type, items_json FROM purchases
             WHERE lead_id = ? AND status IN ($statusPlaceholders)"
        );
        $stmt->execute(array_merge([$leadId], $statuses));

        $billingTypes = ['SALE', 'BILL', 'TEST_SALE', 'TEST_BILL'];
        $entitlementIds = [];
        $anchorIds = [];
        while (($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {
            $raw = $row['items_json'] ?? '';
            if (!is_string($raw) || $raw === '') {
                continue;
            }
            try {
                $items = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
            } catch (JsonException) {
                continue;
            }
            if (!is_array($items) || !$this->purchaseContainsAnySku($items, $skus)) {
                continue;
            }

            $id = (int) $row['id'];
            $entitlementIds[] = $id;

            $txnType = strtoupper(trim((string) ($row['txn_type'] ?? '')));
            if (in_array($txnType, $billingTypes, true)) {
                $anchorIds[] = $id;
            }
        }

        return [$entitlementIds, $anchorIds];
    }

    /**
     * Computes the paid-through timestamp (max anchor created_at + 1 month, floored to now)
     * DB-side, using the driver's date functions so it stays timezone-safe.
     *
     * @param list<int> $anchorIds
     */
    private function computeAccessUntil(string $anchorPlaceholders, array $anchorIds): ?string
    {
        $driver = (string) $this->pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
        $sql = $driver === 'sqlite'
            ? "SELECT MAX(
                    CASE
                        WHEN datetime(created_at, '+1 month') > CURRENT_TIMESTAMP
                            THEN datetime(created_at, '+1 month')
                        ELSE CURRENT_TIMESTAMP
                    END
               )
               FROM purchases WHERE id IN ($anchorPlaceholders)"
            : "SELECT GREATEST(NOW(), DATE_ADD(MAX(created_at), INTERVAL 1 MONTH))
               FROM purchases WHERE id IN ($anchorPlaceholders)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($anchorIds);
        $value = $stmt->fetchColumn();

        return is_string($value) && $value !== '' ? $value : null;
    }

    /**
     * Rebill heartbeat: extends this lead's Inner Circle access window on an APPROVED billing event
     * (SALE / BILL / TEST_SALE / TEST_BILL / UNCANCEL*), so access self-expires at period end when
     * rebills stop. A single missed cancel INS can never leave a paying-nothing member with
     * permanent access, because each payment only buys one month plus a grace window.
     *
     * Sets access_until = $anchorTime (or NOW() when null) + 1 month + $graceDays days on every
     * approved purchase row for this lead that contains one of $skus, and NEVER shortens a later
     * existing access_until (extend-only). The date math is computed DB-side so it stays
     * timezone-safe, mirroring {@see self::computeAccessUntil()}.
     *
     * @param list<non-empty-string> $skus
     * @param string|null $anchorTime Transaction time as 'Y-m-d H:i:s' (INS payload time), or null for NOW().
     * @return string|null The resulting access window (max access_until across the updated rows), or
     *                     null when this lead holds no approved matching row.
     */
    public function extendInnerCircleAccessWindow(
        int $leadId,
        array $skus,
        ?string $anchorTime = null,
        int $graceDays = 3,
    ): ?string {
        $target = $this->computeHeartbeatTarget($anchorTime, $graceDays);
        if ($target === null) {
            return null;
        }

        return $this->applyInnerCircleAccessUntil($leadId, $skus, $target, true);
    }

    /**
     * Reconciliation setter: stamps access_until = $target on every approved purchase row for this
     * lead that contains one of $skus. When $extendOnly is true it never shortens a later existing
     * value (heals a paying member whose access lapsed after a missed BILL INS); when false it
     * overwrites unconditionally (clamps a cancelled sub down to its true paid-through end).
     *
     * @param list<non-empty-string> $skus
     * @param string $target Target access_until as 'Y-m-d H:i:s'.
     * @return string|null The resulting access window (max access_until across the updated rows), or
     *                     null when this lead holds no approved matching row.
     */
    public function setInnerCircleAccessUntilTo(
        int $leadId,
        array $skus,
        string $target,
        bool $extendOnly,
    ): ?string {
        $target = trim($target);
        if ($target === '') {
            return null;
        }

        return $this->applyInnerCircleAccessUntil($leadId, $skus, $target, $extendOnly);
    }

    /**
     * All distinct ClickBank receipts for approved purchases that contain an Inner Circle SKU,
     * joined to the buyer email, for the reconciliation cron. Ordered lapsed-window-first so the
     * members most likely locked out (or overdue for revocation) are reconciled before fresher ones
     * when a limit is applied.
     *
     * @param int|null $limit Max receipts to return (null = no limit).
     * @return list<array{receipt: string, leadId: int, email: string, accessUntil: string|null, createdAt: string|null, updatedAt: string|null}>
     */
    public function findActiveInnerCircleReceipts(?int $limit = null): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT p.clickbank_receipt AS receipt, p.lead_id AS lead_id, l.email AS email,
                    p.access_until AS access_until, p.created_at AS created_at, p.updated_at AS updated_at,
                    p.items_json AS items_json
             FROM purchases p
             INNER JOIN leads l ON l.id = p.lead_id
             WHERE p.status IN ('approved', 'complete', 'completed', 'active')
               AND p.clickbank_receipt IS NOT NULL AND p.clickbank_receipt <> ''
             ORDER BY (p.access_until IS NULL) DESC, p.access_until ASC, p.id ASC"
        );
        $stmt->execute();

        $out = [];
        $seen = [];
        while (($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {
            $raw = $row['items_json'] ?? '';
            if (!is_string($raw) || $raw === '') {
                continue;
            }
            try {
                $items = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
            } catch (JsonException) {
                continue;
            }
            // RECURRING only: the reconciler reconciles subscriptions against ClickBank. Lifetime
            // one-time rows (tic-2) have no subscription and must never be reconciled, or their
            // permanent (NULL) access window could be stamped with an expiry.
            if (!is_array($items) || !$this->purchaseContainsAnySku($items, InnerCircleSkus::RECURRING)) {
                continue;
            }
            $receipt = trim((string) ($row['receipt'] ?? ''));
            $email = strtolower(trim((string) ($row['email'] ?? '')));
            if ($receipt === '' || $email === '' || isset($seen[$receipt])) {
                continue;
            }
            $seen[$receipt] = true;
            $out[] = [
                'receipt' => $receipt,
                'leadId' => (int) $row['lead_id'],
                'email' => $email,
                'accessUntil' => self::nullableString($row['access_until'] ?? null),
                'createdAt' => self::nullableString($row['created_at'] ?? null),
                'updatedAt' => self::nullableString($row['updated_at'] ?? null),
            ];
            if ($limit !== null && count($out) >= $limit) {
                break;
            }
        }

        return $out;
    }

    /**
     * Buyer + purchase summary for a single receipt, joined to the lead email. Used by the
     * cancel-INS fire drill to build a realistic payload and read back the resulting state.
     *
     * @return array{leadId: int, email: string, status: string, txnType: string|null, accessUntil: string|null, createdAt: string|null, itemsJson: string}|null
     */
    public function findPurchaseWithBuyerByReceipt(string $receipt): ?array
    {
        $receipt = trim($receipt);
        if ($receipt === '') {
            return null;
        }

        $stmt = $this->pdo->prepare(
            "SELECT p.lead_id AS lead_id, l.email AS email, p.status AS status, p.txn_type AS txn_type,
                    p.access_until AS access_until, p.created_at AS created_at, p.items_json AS items_json
             FROM purchases p
             INNER JOIN leads l ON l.id = p.lead_id
             WHERE p.clickbank_receipt = :receipt
             LIMIT 1"
        );
        $stmt->execute([':receipt' => $receipt]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!is_array($row)) {
            return null;
        }

        return [
            'leadId' => (int) $row['lead_id'],
            'email' => strtolower(trim((string) ($row['email'] ?? ''))),
            'status' => (string) ($row['status'] ?? ''),
            'txnType' => self::nullableString($row['txn_type'] ?? null),
            'accessUntil' => self::nullableString($row['access_until'] ?? null),
            'createdAt' => self::nullableString($row['created_at'] ?? null),
            'itemsJson' => is_string($row['items_json'] ?? null) ? (string) $row['items_json'] : '',
        ];
    }

    /**
     * Stamps access_until = $target on this lead's approved rows that contain any of $skus,
     * returning the resulting window. Shared by the rebill heartbeat and the reconciliation setter.
     *
     * @param list<non-empty-string> $skus
     */
    private function applyInnerCircleAccessUntil(
        int $leadId,
        array $skus,
        string $target,
        bool $extendOnly,
    ): ?string {
        if ($skus === []) {
            return null;
        }

        $stmt = $this->pdo->prepare(
            "SELECT id, items_json FROM purchases
             WHERE lead_id = :lead_id
               AND status IN ('approved', 'complete', 'completed', 'active')"
        );
        $stmt->execute([':lead_id' => $leadId]);

        $entitlementIds = [];
        while (($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {
            $raw = $row['items_json'] ?? '';
            if (!is_string($raw) || $raw === '') {
                continue;
            }
            try {
                $items = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
            } catch (JsonException) {
                continue;
            }
            if (!is_array($items) || !$this->purchaseContainsAnySku($items, $skus)) {
                continue;
            }
            $entitlementIds[] = (int) $row['id'];
        }

        if ($entitlementIds === []) {
            return null;
        }

        $placeholders = implode(', ', array_fill(0, count($entitlementIds), '?'));
        // Extend-only never moves a later paid-through date earlier (a NULL window is treated as
        // "needs stamping"); overwrite clamps unconditionally to the cancelled paid-through end.
        $setExpr = $extendOnly
            ? 'CASE WHEN access_until IS NULL OR access_until < ? THEN ? ELSE access_until END'
            : '?';
        $update = $this->pdo->prepare(
            "UPDATE purchases
             SET access_until = $setExpr, updated_at = CURRENT_TIMESTAMP
             WHERE id IN ($placeholders)"
        );
        $params = $extendOnly ? [$target, $target] : [$target];
        $update->execute(array_merge($params, $entitlementIds));

        return $this->maxAccessUntil($entitlementIds);
    }

    /**
     * Computes $anchorTime (or NOW() when null) + 1 month + $graceDays days DB-side, using the
     * driver's date functions so it stays timezone-safe. $graceDays is cast to a non-negative int
     * (never interpolated from user input) so inlining it in the SQL is safe.
     */
    private function computeHeartbeatTarget(?string $anchorTime, int $graceDays): ?string
    {
        $grace = max(0, $graceDays);
        $driver = (string) $this->pdo->getAttribute(PDO::ATTR_DRIVER_NAME);

        if ($driver === 'sqlite') {
            $anchorExpr = $anchorTime === null ? "'now'" : '?';
            $sql = "SELECT datetime($anchorExpr, '+1 month', '+$grace days')";
        } else {
            $anchorExpr = $anchorTime === null ? 'NOW()' : '?';
            $sql = "SELECT DATE_ADD(DATE_ADD($anchorExpr, INTERVAL 1 MONTH), INTERVAL $grace DAY)";
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($anchorTime === null ? [] : [$anchorTime]);
        $value = $stmt->fetchColumn();

        return is_string($value) && $value !== '' ? $value : null;
    }

    /**
     * Max access_until across the given purchase ids, or null when none carry a window.
     *
     * @param list<int> $ids
     */
    private function maxAccessUntil(array $ids): ?string
    {
        if ($ids === []) {
            return null;
        }

        $placeholders = implode(', ', array_fill(0, count($ids), '?'));
        $stmt = $this->pdo->prepare("SELECT MAX(access_until) FROM purchases WHERE id IN ($placeholders)");
        $stmt->execute($ids);
        $value = $stmt->fetchColumn();

        return is_string($value) && $value !== '' ? $value : null;
    }

    private static function nullableString(mixed $value): ?string
    {
        if (!is_string($value)) {
            return null;
        }
        $value = trim($value);

        return $value !== '' ? $value : null;
    }

    /**
     * @param array<int,array<string,mixed>> $items
     * @param array<string,mixed> $rawInsPayload
     */
    /**
     * Seconds remaining in a post-purchase window (the "preparing your reading" gate),
     * measured from the buyer's EARLIEST approved purchase. Returns 0 once the window has
     * elapsed (existing buyers) or if there is no approved purchase. Computed entirely
     * DB-side via NOW() so it is timezone-safe, mirroring ReadingDeliveryRepository.
     */
    public function purchaseUnlockSecondsRemaining(int $leadId, int $windowSeconds): int
    {
        $window = max(0, $windowSeconds);
        $stmt = $this->pdo->prepare(
            "SELECT GREATEST(0, " . $window . " - TIMESTAMPDIFF(SECOND, created_at, NOW())) AS s
             FROM purchases
             WHERE lead_id = :lead_id AND status IN ('approved', 'complete', 'completed', 'active')
             ORDER BY created_at ASC
             LIMIT 1"
        );
        $stmt->execute([':lead_id' => $leadId]);
        $value = $stmt->fetchColumn();

        return $value === false ? 0 : (int) $value;
    }

    public function upsertByReceipt(
        int $leadId,
        string $receipt,
        ?string $txnType,
        string $status,
        ?string $currency,
        ?float $amount,
        array $items,
        array $rawInsPayload,
    ): void {
        $driver = (string) $this->pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
        $sql = $driver === 'sqlite'
            ? 'INSERT INTO purchases (
                    lead_id, clickbank_receipt, txn_type, status, currency, amount, items_json, raw_ins_json
               ) VALUES (
                    :lead_id, :clickbank_receipt, :txn_type, :status, :currency, :amount, :items_json, :raw_ins_json
               )
               ON CONFLICT(clickbank_receipt) DO UPDATE SET
                    lead_id = excluded.lead_id,
                    txn_type = excluded.txn_type,
                    status = excluded.status,
                    currency = excluded.currency,
                    amount = excluded.amount,
                    items_json = excluded.items_json,
                    raw_ins_json = excluded.raw_ins_json,
                    updated_at = CURRENT_TIMESTAMP'
            : 'INSERT INTO purchases (
                    lead_id, clickbank_receipt, txn_type, status, currency, amount, items_json, raw_ins_json
               ) VALUES (
                    :lead_id, :clickbank_receipt, :txn_type, :status, :currency, :amount, :items_json, :raw_ins_json
               )
               ON DUPLICATE KEY UPDATE
                    lead_id = VALUES(lead_id),
                    txn_type = VALUES(txn_type),
                    status = VALUES(status),
                    currency = VALUES(currency),
                    amount = VALUES(amount),
                    items_json = VALUES(items_json),
                    raw_ins_json = VALUES(raw_ins_json),
                    updated_at = CURRENT_TIMESTAMP';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':lead_id' => $leadId,
            ':clickbank_receipt' => $receipt,
            ':txn_type' => $txnType,
            ':status' => $status,
            ':currency' => $currency,
            ':amount' => $amount,
            ':items_json' => json_encode($items, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR),
            ':raw_ins_json' => json_encode($rawInsPayload, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR),
        ]);
    }
}
