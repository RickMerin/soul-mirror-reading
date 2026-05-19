<?php

declare(strict_types=1);

namespace App\Services;

use App\Domain\TarotPredictionResult;
use App\Services\AstrologyApiException;

/**
 * Tarot reading text for three frontend card ids (love / life / wealth slots).
 */
interface TarotPredictionProvider
{
    /**
     * @throws AstrologyApiException When upstream tarot fetch fails (API mode)
     */
    public function predict(int $loveCard, int $careerCard, int $financeCard): TarotPredictionResult;
}
