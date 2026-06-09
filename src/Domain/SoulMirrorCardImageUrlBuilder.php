<?php

declare(strict_types=1);

namespace App\Domain;

/**
 * Builds public Soul Mirror card image URLs (soulmirrorreading.com/cards/NN-slug.png).
 */
final class SoulMirrorCardImageUrlBuilder
{
    public function __construct(
        private readonly string $cardsBaseUrl = 'https://soulmirrorreading.com/cards',
    ) {}

    /**
     * @param int|string $cardId 1–78 frontend id
     */
    public function buildUrl(int|string $cardId): string
    {
        $n = is_int($cardId) ? $cardId : (int) $cardId;
        $slug = TarotCardCatalog::slugForFrontendId($n);
        if ($slug === null) {
            return '';
        }

        return rtrim($this->cardsBaseUrl, '/') . '/' . sprintf('%02d', $n) . '-' . $slug . '.png';
    }

    public function buildUrlForCardName(string $cardName): string
    {
        $needle = strtolower(trim($cardName));
        foreach (TarotCardCatalog::all() as $card) {
            if (strtolower($card->name) === $needle) {
                return $this->buildUrl($card->frontendId);
            }
        }

        return '';
    }
}
