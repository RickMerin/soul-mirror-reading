<?php

declare(strict_types=1);

/**
 * POST /api/reading — JSON body → tarot + sun (optional) → Kit subscriber + tag.
 *
 * Bootstrap path: this file lives in `public/api/`, project root is two levels up.
 */

use App\Application\ReadingOrchestrator;
use App\Application\ReadingServiceFactory;
use App\Config\AppConfig;
use App\Domain\CardImageUrlBuilder;
use App\Domain\MirrorBlockResolver;
use App\Domain\SunSignResolver;
use App\Infrastructure\DatabaseConnection;
use App\Logging\PipelineLogger;
use App\Repository\LeadRepository;
use App\Repository\PurchaseRepository;
use App\Services\KitService;
use App\Services\ReadingDeliveryTrigger;
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
$http = new Client($config->guzzleClientConfig());
$pipelineLog = new PipelineLogger($config);
$leadRepository = null;
$pdo = null;
if ($config->hasDatabaseConfig()) {
    try {
        $pdo = DatabaseConnection::fromConfig($config);
        $leadRepository = new LeadRepository($pdo);
    } catch (Throwable $e) {
        error_log('reading.php database init failed: ' . $e->getMessage());
    }
}
$orchestrator = new ReadingOrchestrator(
    $config,
    ReadingServiceFactory::tarotProvider($config, $http),
    ReadingServiceFactory::sunSignProvider($config, $http),
    new KitService($config, $http),
    new SunSignResolver(),
    new CardImageUrlBuilder(),
    new MirrorBlockResolver(),
    $pipelineLog,
    $leadRepository,
);

try {
    $result = $orchestrator->run($body);
} catch (Throwable $e) {
    // Any uncaught failure in the reading pipeline must return JSON, never an empty
    // fatal 500 (which the browser surfaces to the visitor as "Something went wrong").
    error_log('reading.php pipeline error: ' . $e::class . ' ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Internal server error.'], JSON_UNESCAPED_UNICODE);

    exit;
}

// Best-effort: if this (returning) buyer already has a paid main reading, queue its
// delivery now. This is a side optimization and MUST NOT break the opt-in response,
// so any failure here (e.g. a DB/query hiccup) is logged and swallowed. A brand-new
// opt-in lead has no purchase yet, so this normally finds nothing and no-ops.
if ($result->httpStatus === 200 && $leadRepository !== null && $pdo !== null) {
    try {
        $email = strtolower(trim((string) ($body['email'] ?? '')));
        if ($email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $leadId = $leadRepository->findIdByEmail($email);
            if ($leadId !== null) {
                $purchaseId = (new PurchaseRepository($pdo))->findDeliverableMainReadingPurchaseId($leadId);
                if ($purchaseId !== null) {
                    (new ReadingDeliveryTrigger($projectRoot))->queuePurchaseDelivery($purchaseId);
                }
            }
        }
    } catch (Throwable $e) {
        error_log('reading.php delivery-trigger (non-fatal): ' . $e::class . ' ' . $e->getMessage());
    }
}

http_response_code($result->httpStatus);
echo json_encode($result->json, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
