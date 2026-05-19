<?php

declare(strict_types=1);

namespace App\Domain;

/**
 * Builds public card image URLs (trustedtarot.com) from 1-based tarot card IDs.
 */
final class CardImageUrlBuilder
{
    private const BASE = 'https://www.trustedtarot.com/img/cards/';

    /**
     * @param int|string $cardId 1–78 as sent by the client
     */
    public function buildUrl(int|string $cardId): string
    {
        $n = is_int($cardId) ? $cardId : (int) $cardId;
        $slug = TarotCardCatalog::slugForFrontendId($n);
        if ($slug === null) {
            return '';
        }

        return self::BASE . $slug . '.png';
    }
}
