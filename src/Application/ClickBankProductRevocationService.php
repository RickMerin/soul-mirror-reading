<?php

declare(strict_types=1);

namespace App\Application;

use App\Domain\ClickBankPurchaseStatus;
use App\Domain\InnerCircleSkus;
use App\Repository\PurchaseRepository;
use App\Services\InnerCircleRevocationNotifier;

/**
 * Revokes per-product entitlements when ClickBank sends cancel/refund/chargeback INS events.
 */
final class ClickBankProductRevocationService
{
    public function __construct(
        private readonly PurchaseRepository $purchases,
        private readonly InnerCircleRevocationNotifier $innerCircleNotifier,
    ) {}

    /**
     * @param array<int, array<string, mixed>> $items INS line items for the current event
     */
    public function revokeForInsEvent(
        int $leadId,
        string $email,
        array $items,
        string $status,
        ?string $receipt,
    ): int {
        if (!ClickBankPurchaseStatus::isRevoked($status)) {
            return 0;
        }

        if (!$this->shouldRevokeInnerCircle($leadId, $items, $receipt)) {
            return 0;
        }

        // A cancelled rebill means the buyer stopped future billing but already paid for the
        // current period, so keep Inner Circle access until the paid period ends (soft cancel).
        // Refunds and chargebacks reverse the payment, so they revoke access immediately.
        if (self::isCancellation($status)) {
            $accessUntil = $this->purchases->setInnerCircleAccessUntil($leadId, InnerCircleSkus::ALL);

            // Defense in depth: a cancel on an Inner Circle item must never produce zero side
            // effects. When no approved or cancelled row yields a paid-through window (e.g. the
            // only matching rows are refunded), a null window tells the Worker to revoke now.
            $this->innerCircleNotifier->notifyRevoked($email, $status, $receipt, $accessUntil);

            return 1;
        }

        // Refunds / chargebacks end access immediately. The INS upsert may already have flipped
        // the event receipt to refunded before we run, so sibling revoke can be 0 — still notify
        // the Worker with accessUntil=null so a prior soft-cancel window cannot keep the session.
        $revoked = $this->purchases->revokeApprovedPurchasesContainingSkus(
            $leadId,
            InnerCircleSkus::ALL,
            $status,
        );

        if ($this->purchases->leadHasApprovedInnerCirclePurchase($leadId)) {
            return $revoked;
        }

        $this->innerCircleNotifier->notifyRevoked($email, $status, $receipt, null);

        return max($revoked, 1);
    }

    private static function isCancellation(string $status): bool
    {
        return strtolower(trim($status)) === 'cancelled';
    }

    /**
     * @param array<int, array<string, mixed>> $items
     */
    private function shouldRevokeInnerCircle(int $leadId, array $items, ?string $receipt): bool
    {
        if (InnerCircleSkus::purchaseIncludesInnerCircle($items)) {
            return true;
        }

        if ($receipt === null || $receipt === '') {
            return false;
        }

        $purchaseId = $this->purchases->findIdByReceipt($receipt);
        if ($purchaseId === null) {
            return false;
        }

        return $this->purchases->leadHasApprovedInnerCirclePurchase($leadId);
    }
}
