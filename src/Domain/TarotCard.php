<?php

declare(strict_types=1);

namespace App\Domain;

/**
 * One tarot card with frontend id (UI) and AstrologyAPI id.
 */
final class TarotCard
{
    public function __construct(
        public readonly int $frontendId,
        public readonly int $apiId,
        public readonly string $slug,
        public readonly string $name,
    ) {}
}
