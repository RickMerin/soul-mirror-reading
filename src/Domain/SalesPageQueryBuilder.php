<?php

declare(strict_types=1);

namespace App\Domain;

/**
 * Builds sales-page personalization query strings for funnel and email links.
 */
final class SalesPageQueryBuilder
{
    private const CARD_SLOTS = ['love', 'life', 'wealth'];

    /**
     * @param array{
     *   first_name?: string,
     *   love_card?: string,
     *   life_card?: string,
     *   wealth_card?: string,
     *   love_card_image?: string,
     *   life_card_image?: string,
     *   wealth_card_image?: string,
     *   mirror_block_name?: string,
     *   mirror_block_summary?: string,
     * } $params
     */
    public function buildQueryString(array $params): string
    {
        $query = [];
        $firstName = trim((string) ($params['first_name'] ?? ''));
        if ($firstName !== '') {
            $query['first_name'] = $firstName;
        }

        foreach (self::CARD_SLOTS as $slot) {
            $nameKey = "{$slot}_card";
            $imageKey = "{$slot}_card_image";
            $name = trim((string) ($params[$nameKey] ?? ''));
            $image = trim((string) ($params[$imageKey] ?? ''));
            if ($name !== '') {
                $query[$nameKey] = $name;
            }
            if ($image !== '') {
                $query[$imageKey] = $image;
            }
        }

        foreach (['mirror_block_name', 'mirror_block_summary'] as $key) {
            $value = trim((string) ($params[$key] ?? ''));
            if ($value !== '') {
                $query[$key] = $value;
            }
        }

        if ($query === []) {
            return '';
        }

        return '?' . http_build_query($query, '', '&', PHP_QUERY_RFC3986);
    }
}
