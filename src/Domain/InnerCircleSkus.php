<?php

declare(strict_types=1);

namespace App\Domain;

/**
 * ClickBank SKUs that grant Inner Circle (Luna chat) access.
 */
final class InnerCircleSkus
{
    /**
     * Recurring subscription SKUs. These get the rebill access-window heartbeat and are the only
     * SKUs the subscription reconciler touches; their access self-expires if rebills stop.
     *
     * @var list<non-empty-string>
     */
    public const RECURRING = [
        'tic-1',
        'tic-1-ds',
        'ic-1',
        'ic-1-ds',
    ];

    /**
     * One-time / lifetime SKUs. These grant PERMANENT access: no rebill, so no access window is
     * ever stamped (access_until stays NULL = never expires). A refund / chargeback still revokes
     * them because it flips the purchase status away from approved.
     *
     * @var list<non-empty-string>
     */
    public const LIFETIME = [
        'tic-2',
    ];

    /**
     * Every SKU that grants Inner Circle (Luna chat) access. Use for the access gate, entitlement
     * checks, and revocation recognition. Do NOT use for the rebill heartbeat or the reconciler:
     * those are recurring-only (see self::RECURRING), or a lifetime buyer would be expired.
     *
     * @var list<non-empty-string>
     */
    public const ALL = [
        'tic-1',
        'tic-1-ds',
        'ic-1',
        'ic-1-ds',
        'tic-2',
    ];

    /**
     * @param array<int, array<string, mixed>> $lineItems
     */
    public static function purchaseIncludesInnerCircle(array $lineItems): bool
    {
        foreach ($lineItems as $item) {
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
                if ($needle !== '' && in_array($needle, self::ALL, true)) {
                    return true;
                }
            }
        }

        return false;
    }
}
