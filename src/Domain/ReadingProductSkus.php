<?php

declare(strict_types=1);

namespace App\Domain;

/**
 * ClickBank SKUs that trigger personalized Soul Mirror Reading PDF delivery.
 */
final class ReadingProductSkus
{
    /** @var list<non-empty-string> */
    public const MAIN_READING = [
        'smr-1',
        'smr-1-w',
        'smr-1-wtsl',
        'smr-1-l',
        'smr-1-p',
        // Live-chat funnel front-ends. Same $37 Soul Mirror Reading PDF, tracked as separate
        // items so live-chat sales attribute cleanly. Must deliver like any other front-end.
        'smr-1-w-lc',
        'smr-1-l-lc',
    ];

    /**
     * @param array<int, array<string, mixed>> $lineItems
     */
    public static function purchaseIncludesMainReading(array $lineItems): bool
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
                if (!is_string($val)) {
                    continue;
                }
                $needle = strtolower(trim($val));
                if (in_array($needle, self::MAIN_READING, true)) {
                    return true;
                }
            }
        }

        return false;
    }
}
