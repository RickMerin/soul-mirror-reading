<?php

declare(strict_types=1);

namespace App\Repository;

use PDO;

final class PurchaseRepository
{
    public function __construct(private readonly PDO $pdo) {}

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
