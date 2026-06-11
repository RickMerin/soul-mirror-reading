<?php

declare(strict_types=1);

use App\Application\ReadingDeliveryFactory;
use App\Config\AppConfig;
use App\Infrastructure\DatabaseConnection;
use App\Services\S3ReadingStorage;
use GuzzleHttp\Client;

$projectRoot = dirname(__DIR__, 2);
require $projectRoot . '/vendor/autoload.php';

header('Content-Type: application/json; charset=utf-8');

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'GET') {
    http_response_code(405);
    header('Allow: GET');
    echo json_encode(['error' => 'Method not allowed.'], JSON_UNESCAPED_UNICODE);
    exit;
}

$config = AppConfig::load($projectRoot);

if ($config->deliveryCronKey === '') {
    http_response_code(503);
    echo json_encode(['error' => 'Delivery cron is not configured.'], JSON_UNESCAPED_UNICODE);
    exit;
}

$providedKey = isset($_GET['key']) && is_string($_GET['key']) ? $_GET['key'] : '';
if ($providedKey === '' || !hash_equals($config->deliveryCronKey, $providedKey)) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized.'], JSON_UNESCAPED_UNICODE);
    exit;
}

if (!$config->hasDatabaseConfig()) {
    http_response_code(503);
    error_log('run-delivery.php database config missing');
    echo json_encode(['error' => 'Delivery worker is not configured.'], JSON_UNESCAPED_UNICODE);
    exit;
}

$storage = new S3ReadingStorage($config);
if (!$storage->isConfigured()) {
    http_response_code(503);
    error_log('run-delivery.php S3 config missing');
    echo json_encode(['error' => 'Delivery worker is not configured.'], JSON_UNESCAPED_UNICODE);
    exit;
}

$limit = 1;
if (isset($_GET['limit'])) {
    $parsed = filter_var($_GET['limit'], FILTER_VALIDATE_INT);
    if (is_int($parsed) && $parsed > 0) {
        $limit = min($parsed, 5);
    }
}

set_time_limit(120);

try {
    $pdo = DatabaseConnection::fromConfig($config);
    $http = new Client($config->guzzleClientConfig());
    $orchestrator = ReadingDeliveryFactory::orchestrator($config, $pdo, $http, $projectRoot);
    $result = $orchestrator->processPendingBatch($limit);

    http_response_code(200);
    echo json_encode([
        'ok' => $result['failed'] === 0,
        'queued' => $result['queued'],
        'success' => $result['success'],
        'failed' => $result['failed'],
        'purchase_ids' => $result['purchase_ids'],
    ], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    http_response_code(500);
    error_log('run-delivery.php failed: ' . $e::class);
    echo json_encode(['error' => 'Delivery run failed.'], JSON_UNESCAPED_UNICODE);
}
