<?php

declare(strict_types=1);

namespace App\Domain;

/**
 * Builds public card image URLs (trustedtarot.com) from 1-based tarot card IDs.
 */
final class CardImageUrlBuilder
{
    private const BASE = 'https://www.trustedtarot.com/img/cards/';

    /** @var list<non-empty-string> */
    private const SLUGS = [
        'the-fool', 'the-magician', 'the-high-priestess', 'the-empress', 'the-emperor',
        'the-heirophant', 'the-lovers', 'the-chariot', 'strength', 'the-hermit',
        'wheel-of-fortune', 'justice', 'the-hanged-man', 'death', 'temperance',
        'the-devil', 'the-tower', 'the-star', 'the-moon', 'the-sun',
        'judgement', 'the-world',
        'ace-of-wands', 'two-of-wands', 'three-of-wands', 'four-of-wands', 'five-of-wands',
        'six-of-wands', 'seven-of-wands', 'eight-of-wands', 'nine-of-wands', 'ten-of-wands',
        'page-of-wands', 'knight-of-wands', 'queen-of-wands', 'king-of-wands',
        'ace-of-cups', 'two-of-cups', 'three-of-cups', 'four-of-cups', 'five-of-cups',
        'six-of-cups', 'seven-of-cups', 'eight-of-cups', 'nine-of-cups', 'ten-of-cups',
        'page-of-cups', 'knight-of-cups', 'queen-of-cups', 'king-of-cups',
        'ace-of-swords', 'two-of-swords', 'three-of-swords', 'four-of-swords', 'five-of-swords',
        'six-of-swords', 'seven-of-swords', 'eight-of-swords', 'nine-of-swords', 'ten-of-swords',
        'page-of-swords', 'knight-of-swords', 'queen-of-swords', 'king-of-swords',
        'ace-of-pentacles', 'two-of-pentacles', 'three-of-pentacles', 'four-of-pentacles', 'five-of-pentacles',
        'six-of-pentacles', 'seven-of-pentacles', 'eight-of-pentacles', 'nine-of-pentacles', 'ten-of-pentacles',
        'page-of-pentacles', 'knight-of-pentacles', 'queen-of-pentacles', 'king-of-pentacles',
    ];

    /**
     * @param int|string $cardId 1–78 as sent by the client
     */
    public function buildUrl(int|string $cardId): string
    {
        $n = is_int($cardId) ? $cardId : (int) $cardId;
        $slug = self::SLUGS[$n - 1] ?? null;
        if ($slug === null) {
            return '';
        }

        return self::BASE . $slug . '.png';
    }
}
