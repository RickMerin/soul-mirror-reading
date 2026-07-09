<?php

declare(strict_types=1);

namespace App\Application;

/**
 * Normalized view of a ClickBank subscription's state, distilled from the Orders API v1.3 response
 * for one receipt. The extraction is deliberately tolerant (ClickBank's JSON shape varies by
 * product and API version); only the subscription status and the two dates the reconciler needs
 * are pulled, each from a list of candidate paths. Kept thin on purpose: the decision logic that
 * consumes this lives in {@see SubscriptionReconciler} and is unit tested.
 */
final class SubscriptionApiState
{
    public const STATUS_ACTIVE = 'ACTIVE';
    public const STATUS_CANCELED = 'CANCELED';
    public const STATUS_UNKNOWN = 'UNKNOWN';

    public function __construct(
        public readonly string $status,
        public readonly ?string $nextPaymentDate,
        public readonly ?string $lastBillDate,
    ) {}

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isCanceled(): bool
    {
        return $this->status === self::STATUS_CANCELED;
    }

    /**
     * Builds a normalized state from a decoded Orders API response for one receipt.
     *
     * @param array<string, mixed> $json
     */
    public static function fromOrderResponse(array $json): self
    {
        $rawStatus = self::firstString($json, [
            'status',
            'orderStatus',
            'subscriptionStatus',
            'rebillStatus',
            'order.status',
            'order.orderStatus',
        ]);
        $nextPayment = self::firstString($json, [
            'nextPaymentDate',
            'nextRebillDate',
            'rebillDate',
            'order.nextPaymentDate',
            'subscription.nextPaymentDate',
        ]);
        $lastBill = self::firstString($json, [
            'lastBillDate',
            'lastPaymentDate',
            'transactionDate',
            'orderDate',
            'order.lastBillDate',
            'order.transactionDate',
        ]);

        return new self(self::normalizeStatus($rawStatus), $nextPayment, $lastBill);
    }

    /**
     * Maps a raw ClickBank status onto ACTIVE / CANCELED / UNKNOWN. Cancel, expiry and refund all
     * mean "stop granting access"; only an explicit ACTIVE keeps the subscription live. Anything
     * unrecognized is UNKNOWN so the reconciler leaves it untouched (never a false revoke).
     */
    private static function normalizeStatus(?string $raw): string
    {
        if ($raw === null) {
            return self::STATUS_UNKNOWN;
        }
        $upper = strtoupper(trim($raw));
        if ($upper === '') {
            return self::STATUS_UNKNOWN;
        }
        if (str_contains($upper, 'CANCEL') || str_contains($upper, 'EXPIRED') || str_contains($upper, 'REFUND')) {
            return self::STATUS_CANCELED;
        }
        if (str_contains($upper, 'ACTIVE')) {
            return self::STATUS_ACTIVE;
        }

        return self::STATUS_UNKNOWN;
    }

    /**
     * First non-empty string/numeric value found among the candidate dot-paths.
     *
     * @param array<string, mixed> $json
     * @param list<string> $paths
     */
    private static function firstString(array $json, array $paths): ?string
    {
        foreach ($paths as $path) {
            $value = self::valueByPath($json, $path);
            if (is_string($value) && trim($value) !== '') {
                return trim($value);
            }
            if (is_numeric($value)) {
                return (string) $value;
            }
        }

        return null;
    }

    /**
     * @param array<string, mixed> $json
     */
    private static function valueByPath(array $json, string $path): mixed
    {
        $current = $json;
        foreach (explode('.', $path) as $segment) {
            if (!is_array($current) || !array_key_exists($segment, $current)) {
                return null;
            }
            $current = $current[$segment];
        }

        return $current;
    }
}
