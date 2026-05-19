<?php

declare(strict_types=1);

namespace App\Services;

/**
 * Fetches daily sun_sign_prediction via AstrologyAPI.
 */
final class ApiSunSignPredictionProvider implements SunSignPredictionProvider
{
    public function __construct(
        private readonly AstrologyApiClient $client,
    ) {}

    public function forSign(string $sign): array
    {
        return $this->client->fetchSunPrediction($sign);
    }
}
