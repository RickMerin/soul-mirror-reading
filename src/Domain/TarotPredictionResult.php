<?php

declare(strict_types=1);

namespace App\Domain;

/**
 * Three slot readings from tarot_predictions (love / career / finance).
 */
final class TarotPredictionResult
{
    public function __construct(
        public readonly string $love,
        public readonly string $career,
        public readonly string $finance,
    ) {}
}
