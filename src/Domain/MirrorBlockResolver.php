<?php

declare(strict_types=1);

namespace App\Domain;

/**
 * Resolves a Mirror Block slug from the buyer's three drawn cards.
 *
 * Deterministic mapping: sorted card ids → one of four canonical slugs.
 * Replace with a curated lookup table when the full 22,100-combination map is available.
 */
final class MirrorBlockResolver
{
    /**
     * @param list<int> $cardIds Three frontend card ids (1–78), any order
     */
    public function resolveFromCardIds(array $cardIds): string
    {
        $ids = array_values(array_filter(
            array_map(static fn (mixed $id): int => is_numeric($id) ? (int) $id : 0, $cardIds),
            static fn (int $id): bool => $id >= 1 && $id <= 78,
        ));
        if (count($ids) !== 3) {
            return 'not-yet-ready';
        }

        sort($ids, SORT_NUMERIC);
        $hash = crc32(implode('-', array_map(static fn (int $id): string => (string) $id, $ids)));
        $slugs = MirrorBlockCatalog::allSlugs();
        $index = (int) ($hash % count($slugs));

        return $slugs[$index];
    }

    /**
     * @param list<array{id?: int|string, name?: string}> $cards
     */
    public function resolveFromCards(array $cards): string
    {
        $ids = [];
        foreach ($cards as $card) {
            if (!is_array($card)) {
                continue;
            }
            if (isset($card['id']) && is_numeric($card['id'])) {
                $ids[] = (int) $card['id'];
            }
        }

        return $this->resolveFromCardIds($ids);
    }
}
