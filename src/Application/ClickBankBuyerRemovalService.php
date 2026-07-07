<?php

declare(strict_types=1);

namespace App\Application;

use App\Domain\ClickBankPurchaseStatus;
use App\Repository\LeadRepository;
use App\Repository\PurchaseRepository;
use App\Repository\ReadingDeliveryRepository;
use App\Services\S3ReadingStorage;
use Throwable;

/**
 * Removes buyer database records when all purchases are revoked (refund/chargeback/cancel).
 */
final class ClickBankBuyerRemovalService
{
    public function __construct(
        private readonly LeadRepository $leads,
        private readonly PurchaseRepository $purchases,
        private readonly ReadingDeliveryRepository $deliveries,
        private readonly S3ReadingStorage $s3,
    ) {}

    public function removeBuyerIfFullyRevoked(int $leadId, string $newStatus): void
    {
        if (!ClickBankPurchaseStatus::isRevoked($newStatus)) {
            return;
        }

        if ($this->purchases->buyerHasAnyPurchase($leadId)) {
            return;
        }

        if ($this->s3->isConfigured()) {
            foreach ($this->deliveries->findS3ObjectKeysByLeadId($leadId) as $objectKey) {
                try {
                    $this->s3->deleteObject($objectKey);
                } catch (Throwable $e) {
                    error_log('ClickBank buyer removal S3 delete failed: ' . $e->getMessage());
                }
            }
        }

        $this->leads->deleteById($leadId);
    }
}
