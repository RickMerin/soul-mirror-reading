#!/usr/bin/env php
<?php

declare(strict_types=1);

/**
 * Fetches daily sun_sign_prediction for all 12 signs → data/sun-sign/YYYY-MM-DD.json
 *
 * Requires ASTRO_USER_ID and ASTRO_API_KEY in .env.
 * php scripts/refresh-sun-sign.php [--date=YYYY-MM-DD] [--sleep-ms=250]
 */

$projectRoot = dirname(__DIR__);

require $projectRoot . '/vendor/autoload.php';

use App\Config\AppConfig;
use App\Services\AstrologyApiClient;
use DateTimeImmutable;
use DateTimeZone;
use GuzzleHttp\Client;

$config = AppConfig::load($projectRoot);

if ($config->astroUserId === '' || $config->astroApiKey === '') {
    fwrite(STDERR, "ASTRO_USER_ID and ASTRO_API_KEY must be set in .env\n");
    exit(1);
}

$sleepMs = 250;
$dateOverride = null;
foreach ($argv as $arg) {
    if (str_starts_with($arg, '--sleep-ms=')) {
        $sleepMs = max(0, (int) substr($arg, strlen('--sleep-ms=')));
    }
    if (str_starts_with($arg, '--date=')) {
        $dateOverride = substr($arg, strlen('--date='));
    }
}

try {
    $tz = new DateTimeZone($config->sunSignTimezone);
} catch (Exception) {
    $tz = new DateTimeZone('UTC');
}

$date = $dateOverride ?? (new DateTimeImmutable('now', $tz))->format('Y-m-d');

$signs = [
    'aries', 'taurus', 'gemini', 'cancer', 'leo', 'virgo',
    'libra', 'scorpio', 'sagittarius', 'capricorn', 'aquarius', 'pisces',
];

$http = new Client($config->guzzleClientConfig());
$client = new AstrologyApiClient($config, $http);

$signData = [];
foreach ($signs as $sign) {
    echo "Fetching {$sign}...\n";
    $signData[$sign] = $client->fetchSunPrediction($sign);
    usleep($sleepMs * 1000);
}

$payload = [
    'version' => 1,
    'date' => $date,
    'source' => 'astrologyapi-v1-sun_sign_prediction_daily',
    'generatedAt' => gmdate('c'),
    'signs' => $signData,
];

$dir = $config->sunSignDataDir();
if (!is_dir($dir) && !mkdir($dir, 0755, true) && !is_dir($dir)) {
    fwrite(STDERR, "Cannot create directory: {$dir}\n");
    exit(1);
}

$outPath = $dir . DIRECTORY_SEPARATOR . $date . '.json';
file_put_contents(
    $outPath,
    json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR) . "\n",
);

echo "Wrote {$outPath}\n";
