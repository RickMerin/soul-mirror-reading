<?php

declare(strict_types=1);

namespace App\Application;

use App\Config\AppConfig;
use App\Services\ApiSunSignPredictionProvider;
use App\Services\ApiTarotPredictionProvider;
use App\Services\AstrologyApiClient;
use App\Services\LocalSunSignRepository;
use App\Services\LocalTarotPredictionRepository;
use App\Services\SunSignPredictionProvider;
use App\Services\TarotPredictionProvider;
use DateTimeZone;
use GuzzleHttp\ClientInterface;
use InvalidArgumentException;

/**
 * Wires tarot and sun-sign providers from AppConfig.
 */
final class ReadingServiceFactory
{
    public static function tarotProvider(AppConfig $config, ClientInterface $http): TarotPredictionProvider
    {
        if ($config->tarotSource === 'local') {
            return new LocalTarotPredictionRepository($config->tarotPredictionsPath());
        }
        if ($config->tarotSource === 'api') {
            return new ApiTarotPredictionProvider(new AstrologyApiClient($config, $http));
        }

        throw new InvalidArgumentException('Invalid TAROT_SOURCE: ' . $config->tarotSource);
    }

    public static function sunSignProvider(AppConfig $config, ClientInterface $http): SunSignPredictionProvider
    {
        if ($config->sunSource === 'local') {
            return new LocalSunSignRepository(
                $config->sunSignDataDir(),
                self::sunSignTimezone($config),
            );
        }
        if ($config->sunSource === 'api') {
            return new ApiSunSignPredictionProvider(new AstrologyApiClient($config, $http));
        }

        throw new InvalidArgumentException('Invalid SUN_SOURCE: ' . $config->sunSource);
    }

    private static function sunSignTimezone(AppConfig $config): DateTimeZone
    {
        try {
            return new DateTimeZone($config->sunSignTimezone);
        } catch (\Exception) {
            return new DateTimeZone('UTC');
        }
    }
}
