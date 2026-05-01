<?php

declare(strict_types=1);

namespace App\Domain;

/**
 * Derives Kit tag names from ClickBank INS line items (same keys as PurchaseRepository).
 */
final class ClickBankSkuTags
{
    /**
     * @param array<int, array<string, mixed>> $lineItems
     *
     * @return list<string> Unique SKUs trimmed and lowercased (tag names align with legacy ConvertKit scripts).
     */
    public static function distinctSkuTagNames(array $lineItems): array
    {
        $seen = [];
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
                $s = strtolower(trim((string) $val));
                if ($s === '') {
                    continue;
                }
                $seen[$s] = true;
                break;
            }
        }

        $out = [];
        foreach (array_keys($seen) as $key) {
            $out[] = (string) $key;
        }

        return $out;
    }
}
