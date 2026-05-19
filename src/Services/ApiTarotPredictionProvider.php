<?php

declare(strict_types=1);

namespace App\Services;

use App\Domain\TarotCardCatalog;
use App\Domain\TarotPredictionResult;
use JsonException;

/**
 * Fetches tarot_predictions via AstrologyAPI using frontend→API id mapping.
 */
final class ApiTarotPredictionProvider implements TarotPredictionProvider
{
    public function __construct(
        private readonly AstrologyApiClient $client,
    ) {}

    public function predict(int $loveCard, int $careerCard, int $financeCard): TarotPredictionResult
    {
        $loveApi = TarotCardCatalog::frontendIdToApiId($loveCard);
        $careerApi = TarotCardCatalog::frontendIdToApiId($careerCard);
        $financeApi = TarotCardCatalog::frontendIdToApiId($financeCard);

        try {
            $reading = $this->client->fetchTarotPredictions($loveApi, $careerApi, $financeApi);
        } catch (JsonException $e) {
            throw new AstrologyApiException('Failed to fetch tarot reading.', 0, $e);
        }

        return new TarotPredictionResult(
            love: is_string($reading['love'] ?? null) ? $reading['love'] : '',
            career: is_string($reading['career'] ?? null) ? $reading['career'] : '',
            finance: is_string($reading['finance'] ?? null) ? $reading['finance'] : '',
        );
    }
}
