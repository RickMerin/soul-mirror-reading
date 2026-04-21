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
}
