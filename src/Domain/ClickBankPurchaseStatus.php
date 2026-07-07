<?php

declare(strict_types=1);

namespace App\Domain;

/**
 * Normalized ClickBank purchase statuses stored in purchases.status.
 */
final class ClickBankPurchaseStatus
{
    /** @var list<non-empty-string> */
    public const APPROVED = [
        'approved',
        'complete',
        'completed',
        'active',
    ];

    /** @var list<non-empty-string> */
    public const REVOKED = [
        'refunded',
        'chargeback',
        'cancelled',
    ];

    public static function isApproved(string $status): bool
    {
        return in_array(strtolower(trim($status)), self::APPROVED, true);
    }

    public static function isRevoked(string $status): bool
    {
        return in_array(strtolower(trim($status)), self::REVOKED, true);
    }
}
