<?php

declare(strict_types=1);

namespace App\Repository;

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
     * The paid-through timestamp (max access_until) across this lead's approved Inner Circle rows,
     * or null when none carry a soft-cancel window. Used for logging and Slack display only.
     */
    public function innerCircleAccessUntil(int $leadId): ?string
    {
        $stmt = $this->pdo->prepare(
            "SELECT items_json, access_until FROM purchases
             WHERE lead_id = :lead_id
               AND status IN ('approved', 'complete', 'completed', 'active')
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
     * Marks approved purchases containing any of the given SKUs as revoked.
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
            'UPDATE purchases SET status = :status, updated_at = CURRENT_TIMESTAMP WHERE id = :id'
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
     * @param list<non-empty-string> $skus
     * @return string|null The access_until value that was written (as stored by the driver), or
     *                      null when no approved matching row exists to update.
     */
    public function setInnerCircleAccessUntil(int $leadId, array $skus): ?string
    {
        if ($skus === []) {
            return null;
        }

        // Approved rows that grant the entitlement (these get access_until stamped) and, among
        // them, the billing rows (SALE / BILL / TEST_SALE / TEST_BILL) that anchor the paid period.
        $stmt = $this->pdo->prepare(
            "SELECT id, txn_type, items_json FROM purchases
             WHERE lead_id = :lead_id
               AND status IN ('approved', 'complete', 'completed', 'active')"
        );
        $stmt->execute([':lead_id' => $leadId]);

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

        if ($entitlementIds === []) {
            return null;
        }

        // Anchor on the billing rows when present; otherwise fall back to the entitlement rows so
        // an approved-but-untyped row still yields a sensible paid-through date.
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
