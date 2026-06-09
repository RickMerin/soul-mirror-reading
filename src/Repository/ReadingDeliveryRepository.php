<?php

declare(strict_types=1);

namespace App\Repository;

use PDO;

final class ReadingDeliveryRepository
{
    public function __construct(private readonly PDO $pdo) {}

    public function existsForPurchase(int $purchaseId): bool
    {
        $stmt = $this->pdo->prepare('SELECT id FROM reading_deliveries WHERE purchase_id = :purchase_id LIMIT 1');
        $stmt->execute([':purchase_id' => $purchaseId]);

        return $stmt->fetchColumn() !== false;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findByLeadId(int $leadId): ?array
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM reading_deliveries
             WHERE lead_id = :lead_id AND status IN ('generated', 'emailed')
             ORDER BY generated_at DESC
             LIMIT 1"
        );
        $stmt->execute([':lead_id' => $leadId]);
        $row = $stmt->fetch();

        return is_array($row) ? $row : null;
    }

    public function createPending(int $purchaseId, int $leadId, string $s3ObjectKey): void
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO reading_deliveries (purchase_id, lead_id, s3_object_key, status)
             VALUES (:purchase_id, :lead_id, :s3_object_key, :status)'
        );
        $stmt->execute([
            ':purchase_id' => $purchaseId,
            ':lead_id' => $leadId,
            ':s3_object_key' => $s3ObjectKey,
            ':status' => 'pending',
        ]);
    }

    public function markGenerated(int $purchaseId): void
    {
        $stmt = $this->pdo->prepare(
            "UPDATE reading_deliveries
             SET status = 'generated', generated_at = CURRENT_TIMESTAMP, error_message = NULL
             WHERE purchase_id = :purchase_id"
        );
        $stmt->execute([':purchase_id' => $purchaseId]);
    }

    public function markEmailed(int $purchaseId): void
    {
        $stmt = $this->pdo->prepare(
            "UPDATE reading_deliveries
             SET status = 'emailed', emailed_at = CURRENT_TIMESTAMP
             WHERE purchase_id = :purchase_id"
        );
        $stmt->execute([':purchase_id' => $purchaseId]);
    }

    public function markFailed(int $purchaseId, string $errorMessage): void
    {
        $stmt = $this->pdo->prepare(
            "UPDATE reading_deliveries
             SET status = 'failed', error_message = :error_message
             WHERE purchase_id = :purchase_id"
        );
        $stmt->execute([
            ':purchase_id' => $purchaseId,
            ':error_message' => substr($errorMessage, 0, 2000),
        ]);
    }

    /**
     * Approved main-reading purchases without a delivery row yet.
     *
     * @return list<array<string, mixed>>
     */
    public function findPurchasesAwaitingDelivery(int $limit = 50): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT p.id AS purchase_id, p.lead_id, p.clickbank_receipt, p.items_json,
                    l.email, l.name, l.gender, l.cards_json, l.mirror_block_slug
             FROM purchases p
             INNER JOIN leads l ON l.id = p.lead_id
             LEFT JOIN reading_deliveries d ON d.purchase_id = p.id
             WHERE d.id IS NULL
               AND p.status IN ('approved', 'complete', 'completed', 'active')
             ORDER BY p.created_at ASC
             LIMIT :limit"
        );
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll();

        return is_array($rows) ? $rows : [];
    }
}
