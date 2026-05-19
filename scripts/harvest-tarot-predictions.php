#!/usr/bin/env php
<?php

declare(strict_types=1);

/**
 * One-time harvest: AstrologyAPI tarot_predictions → data/tarot-predictions.json
 *
 * Requires ASTRO_USER_ID and ASTRO_API_KEY in .env.
 * php scripts/harvest-tarot-predictions.php [--sleep-ms=250]
 */

$projectRoot = dirname(__DIR__);

require $projectRoot . '/vendor/autoload.php';

use App\Config\AppConfig;
use App\Domain\TarotCardCatalog;
use App\Services\AstrologyApiClient;
use GuzzleHttp\Client;

$config = AppConfig::load($projectRoot);

if ($config->astroUserId === '' || $config->astroApiKey === '') {
    fwrite(STDERR, "ASTRO_USER_ID and ASTRO_API_KEY must be set in .env\n");
    exit(1);
}

$sleepMs = 250;
foreach ($argv as $arg) {
    if (str_starts_with($arg, '--sleep-ms=')) {
        $sleepMs = max(0, (int) substr($arg, strlen('--sleep-ms=')));
    }
}

$http = new Client($config->guzzleClientConfig());
$client = new AstrologyApiClient($config, $http);

$dummyCareerApi = 2;
$dummyFinanceApi = 54;

echo "Probing slot independence...\n";
$probeA = $client->fetchTarotPredictions(13, $dummyCareerApi, $dummyFinanceApi);
$probeB = $client->fetchTarotPredictions(13, 15, 43);
$loveA = is_string($probeA['love'] ?? null) ? $probeA['love'] : '';
$loveB = is_string($probeB['love'] ?? null) ? $probeB['love'] : '';
if ($loveA !== $loveB) {
    fwrite(STDERR, "Warning: love text may depend on other slots.\n");
} else {
    echo "Love slot appears independent of career/finance dummies.\n";
}

$outPath = $config->tarotPredictionsPath();
$cards = [];

foreach (TarotCardCatalog::all() as $card) {
    $apiId = $card->apiId;
    $id = (string) $card->frontendId;

    echo "Card {$id}/78 {$card->name} (api {$apiId})...\n";

    $loveResp = $client->fetchTarotPredictions($apiId, $dummyCareerApi, $dummyFinanceApi);
    usleep($sleepMs * 1000);
    $careerResp = $client->fetchTarotPredictions($dummyCareerApi, $apiId, $dummyFinanceApi);
    usleep($sleepMs * 1000);
    $financeResp = $client->fetchTarotPredictions($dummyCareerApi, $dummyFinanceApi, $apiId);
    usleep($sleepMs * 1000);

    $cards[$id] = [
        'slug' => $card->slug,
        'name' => $card->name,
        'love' => is_string($loveResp['love'] ?? null) ? trim($loveResp['love']) : '',
        'career' => is_string($careerResp['career'] ?? null) ? trim($careerResp['career']) : '',
        'finance' => is_string($financeResp['finance'] ?? null) ? trim($financeResp['finance']) : '',
    ];
}

$payload = [
    'version' => 1,
    'idScheme' => 'frontend-fool-first',
    'source' => 'astrologyapi-v1-tarot_predictions',
    'generatedAt' => gmdate('c'),
    'cards' => $cards,
];

$dir = dirname($outPath);
if (!is_dir($dir) && !mkdir($dir, 0755, true) && !is_dir($dir)) {
    fwrite(STDERR, "Cannot create directory: {$dir}\n");
    exit(1);
}

file_put_contents(
    $outPath,
    json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR) . "\n",
);

echo "Wrote {$outPath}\n";
