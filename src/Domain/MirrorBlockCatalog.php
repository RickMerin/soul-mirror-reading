<?php

declare(strict_types=1);

namespace App\Domain;

/**
 * Canonical Mirror Block types referenced by the reading PDF template.
 */
final class MirrorBlockCatalog
{
    /** @var list<array{slug: non-empty-string, name: non-empty-string, typeNumber: int, sampleWealthCard: non-empty-string, sampleLoveCard: non-empty-string, sampleLifeCard: non-empty-string}> */
    private const TYPES = [
        [
            'slug' => 'not-yet-ready',
            'name' => 'The Not Yet Ready Block',
            'typeNumber' => 1,
            'sampleWealthCard' => 'King of Pentacles',
            'sampleLoveCard' => 'The Lovers',
            'sampleLifeCard' => 'The Hermit',
        ],
        [
            'slug' => 'waiting-to-end',
            'name' => 'Waiting for the Good Thing to End',
            'typeNumber' => 2,
            'sampleWealthCard' => 'King of Pentacles',
            'sampleLoveCard' => 'The Lovers',
            'sampleLifeCard' => 'The Hermit',
        ],
        [
            'slug' => 'too-much',
            'name' => 'Too Much / Making Yourself Smaller',
            'typeNumber' => 3,
            'sampleWealthCard' => 'King of Pentacles',
            'sampleLoveCard' => 'The Lovers',
            'sampleLifeCard' => 'The Hermit',
        ],
        [
            'slug' => 'cannot-receive-help',
            'name' => 'Cannot Let Others Help',
            'typeNumber' => 4,
            'sampleWealthCard' => 'King of Pentacles',
            'sampleLoveCard' => 'The Lovers',
            'sampleLifeCard' => 'The Hermit',
        ],
    ];

    public static function bySlug(string $slug): ?array
    {
        $needle = strtolower(trim($slug));
        foreach (self::TYPES as $type) {
            if ($type['slug'] === $needle) {
                return $type;
            }
        }

        return null;
    }

    /**
     * @return list<non-empty-string>
     */
    public static function allSlugs(): array
    {
        return array_map(static fn (array $t): string => $t['slug'], self::TYPES);
    }

    public static function typeNumberForSlug(string $slug): ?int
    {
        $type = self::bySlug($slug);

        return $type['typeNumber'] ?? null;
    }
}
