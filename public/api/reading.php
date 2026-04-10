<?php

declare(strict_types=1);

/**
 * POST /api/reading — JSON body → tarot + sun (optional) → Kit subscriber + tag.
 *
 * Bootstrap path: this file lives in `public/api/`, project root is two levels up.
 */

use App\Application\ReadingOrchestrator;
use App\Config\AppConfig;
use App\Domain\CardImageUrlBuilder;
use App\Domain\SunSignResolver;
use App\Logging\PipelineLogger;
use App\Services\AstrologyApiClient;
use App\Services\KitService;
use GuzzleHttp\Client;

$projectRoot = dirname(__DIR__, 2);

require $projectRoot . '/vendor/autoload.php';

header('Content-Type: application/json; charset=utf-8');

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    echo json_encode(['error' => 'Method not allowed.'], JSON_UNESCAPED_UNICODE);

    exit;
}

$raw = file_get_contents('php://input');
if ($raw === false) {
    $raw = '';
}

// Limit payload size (~64KB) to reduce abuse; adjust if needed.
if (strlen($raw) > 65536) {
    http_response_code(413);
    echo json_encode(['error' => 'Payload too large.'], JSON_UNESCAPED_UNICODE);

    exit;
}

try {
    $body = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
} catch (JsonException) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON.'], JSON_UNESCAPED_UNICODE);

    exit;
}

if (!is_array($body)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON.'], JSON_UNESCAPED_UNICODE);

    exit;
}

$config = AppConfig::load($projectRoot);
$http = new Client(['timeout' => 45.0, 'http_errors' => false]);
$pipelineLog = new PipelineLogger($config);
$orchestrator = new ReadingOrchestrator(
    $config,
    new AstrologyApiClient($config, $http),
    new KitService($config, $http),
    new SunSignResolver(),
    new CardImageUrlBuilder(),
    $pipelineLog,
);

$result = $orchestrator->run($body);

http_response_code($result->httpStatus);
echo json_encode($result->json, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
