<?php

declare(strict_types=1);

namespace App\Domain;

/**
 * ClickBank SKUs that grant Inner Circle (Luna chat) access.
 */
final class InnerCircleSkus
{
    /** @var list<non-empty-string> */
    public const ALL = [
        'tic-1',
        'tic-1-ds',
        'ic-1',
        'ic-1-ds',
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
