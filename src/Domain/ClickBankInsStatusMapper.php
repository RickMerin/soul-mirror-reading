<?php

declare(strict_types=1);

namespace App\Domain;

/**
 * Maps ClickBank INS transactionType values to normalized purchases.status values.
 *
 * @see https://support.clickbank.com/en/articles/10535147-instant-notification-service-ins
 */
final class ClickBankInsStatusMapper
{
    /**
     * Derives normalized status from an INS payload.
     * Known transactionType values take precedence over top-level status fields.
     */
    public static function normalizeFromPayload(array $payload): string
    {
        $txnType = self::extractTxnType($payload);
        if ($txnType !== null) {
            $mapped = self::fromTxnType($txnType);
            if ($mapped !== null) {
                return $mapped;
            }
        }

        $status = self::payloadValue($payload, ['status', 'order.orderStatus']);
        if (is_string($status) && trim($status) !== '') {
            return strtolower(trim($status));
        }

        return 'pending';
    }

    /**
     * Maps a ClickBank transactionType to purchases.status, or null when unknown.
     */
    public static function fromTxnType(string $txnType): ?string
    {
        return match (strtoupper(trim($txnType))) {
            'SALE', 'BILL', 'BILLED', 'TEST_SALE', 'TEST_BILL', 'UNCANCEL-REBILL', 'UNCANCEL-TEST-REBILL' => 'approved',
            'RFND', 'REFUND', 'TEST_RFND' => 'refunded',
            'CGBK', 'CHARGEBACK', 'INSF' => 'chargeback',
            'CANCEL-REBILL', 'CANCEL-TEST-REBILL', 'CUSTOMER_AUTH_FAILURE' => 'cancelled',
            default => null,
        };
    }

    public static function extractTxnType(array $payload): ?string
    {
        $txnType = self::payloadValue($payload, ['transactionType', 'txnType', 'transType', 'eventType']);
        if (!is_string($txnType) || trim($txnType) === '') {
            return null;
        }

        return strtoupper(trim($txnType));
    }

    /**
     * @param array<string, mixed> $payload
     * @param list<string> $paths
     */
    private static function payloadValue(array $payload, array $paths): mixed
    {
        foreach ($paths as $path) {
            $value = self::payloadValueByPath($payload, $path);
            if ($value !== null) {
                return $value;
            }
        }

        return null;
    }

    /**
     * @param array<string, mixed> $payload
     */
    private static function payloadValueByPath(array $payload, string $path): mixed
    {
        $current = $payload;
        foreach (explode('.', $path) as $segment) {
            if (!is_array($current) || !array_key_exists($segment, $current)) {
                return null;
            }
            $current = $current[$segment];
        }

        return $current;
    }
}
