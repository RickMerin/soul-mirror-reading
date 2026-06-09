<?php

declare(strict_types=1);

namespace App\Repository;

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
               AND status IN ('approved', 'complete', 'completed', 'active')"
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

    /**
     * @param array<int,array<string,mixed>> $items
     * @param array<string,mixed> $rawInsPayload
     */
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
