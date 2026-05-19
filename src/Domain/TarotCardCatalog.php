<?php

declare(strict_types=1);

namespace App\Domain;

/**
 * Canonical 78-card deck: frontend ids (Fool=1) and AstrologyAPI ids (King of Wands=1).
 *
 * @see https://www.astrologyapi.com/tarot-api-docs/api-ref/196/tarot_predictions
 */
final class TarotCardCatalog
{
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

    /** @var list<non-empty-string> */
    private const NAMES = [
        'The Fool', 'The Magician', 'The High Priestess', 'The Empress', 'The Emperor',
        'The Hierophant', 'The Lovers', 'The Chariot', 'Strength', 'The Hermit',
        'Wheel of Fortune', 'Justice', 'The Hanged Man', 'Death', 'Temperance',
        'The Devil', 'The Tower', 'The Star', 'The Moon', 'The Sun',
        'Judgement', 'The World',
        'Ace of Wands', 'Two of Wands', 'Three of Wands', 'Four of Wands', 'Five of Wands',
        'Six of Wands', 'Seven of Wands', 'Eight of Wands', 'Nine of Wands', 'Ten of Wands',
        'Page of Wands', 'Knight of Wands', 'Queen of Wands', 'King of Wands',
        'Ace of Cups', 'Two of Cups', 'Three of Cups', 'Four of Cups', 'Five of Cups',
        'Six of Cups', 'Seven of Cups', 'Eight of Cups', 'Nine of Cups', 'Ten of Cups',
        'Page of Cups', 'Knight of Cups', 'Queen of Cups', 'King of Cups',
        'Ace of Swords', 'Two of Swords', 'Three of Swords', 'Four of Swords', 'Five of Swords',
        'Six of Swords', 'Seven of Swords', 'Eight of Swords', 'Nine of Swords', 'Ten of Swords',
        'Page of Swords', 'Knight of Swords', 'Queen of Swords', 'King of Swords',
        'Ace of Pentacles', 'Two of Pentacles', 'Three of Pentacles', 'Four of Pentacles', 'Five of Pentacles',
        'Six of Pentacles', 'Seven of Pentacles', 'Eight of Pentacles', 'Nine of Pentacles', 'Ten of Pentacles',
        'Page of Pentacles', 'Knight of Pentacles', 'Queen of Pentacles', 'King of Pentacles',
    ];

    /** @var list<TarotCard>|null */
    private static ?array $cards = null;

    /** @var array<int, TarotCard>|null */
    private static ?array $byFrontend = null;

    /** @var array<int, TarotCard>|null */
    private static ?array $byApi = null;

    /**
     * @return list<TarotCard>
     */
    public static function all(): array
    {
        if (self::$cards !== null) {
            return self::$cards;
        }

        $cards = [];
        foreach (self::SLUGS as $i => $slug) {
            $frontendId = $i + 1;
            $cards[] = new TarotCard(
                frontendId: $frontendId,
                apiId: self::frontendIdToApiId($frontendId),
                slug: $slug,
                name: self::NAMES[$i],
            );
        }

        self::$cards = $cards;
        self::$byFrontend = [];
        self::$byApi = [];
        foreach ($cards as $card) {
            self::$byFrontend[$card->frontendId] = $card;
            self::$byApi[$card->apiId] = $card;
        }

        return self::$cards;
    }

    public static function byFrontendId(int $frontendId): ?TarotCard
    {
        self::all();

        return self::$byFrontend[$frontendId] ?? null;
    }

    public static function byApiId(int $apiId): ?TarotCard
    {
        self::all();

        return self::$byApi[$apiId] ?? null;
    }

    public static function frontendIdToApiId(int $frontendId): int
    {
        if ($frontendId < 1 || $frontendId > 78) {
            throw new \InvalidArgumentException('Frontend card id must be 1–78.');
        }

        if ($frontendId <= 22) {
            return 56 + $frontendId;
        }
        if ($frontendId <= 36) {
            return 37 - $frontendId;
        }
        if ($frontendId <= 50) {
            return 79 - $frontendId;
        }
        if ($frontendId <= 64) {
            return 79 - $frontendId;
        }

        return 121 - $frontendId;
    }

    public static function apiIdToFrontendId(int $apiId): int
    {
        $card = self::byApiId($apiId);
        if ($card === null) {
            throw new \InvalidArgumentException('API card id must be 1–78.');
        }

        return $card->frontendId;
    }

    /**
     * @return non-empty-string|null
     */
    public static function slugForFrontendId(int $frontendId): ?string
    {
        return self::byFrontendId($frontendId)?->slug;
    }
}
