<?php

declare(strict_types=1);

namespace App\Services;

/**
 * Daily sun-sign prediction fields for Kit embed.
 */
interface SunSignPredictionProvider
{
    /**
     * @param non-empty-string $sign Lowercase slug (e.g. aries)
     *
     * @return array<string, string> Keys: personal_life, profession, health, emotions, travel, luck
     */
    public function forSign(string $sign): array;
}
