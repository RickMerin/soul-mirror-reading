#!/usr/bin/env php
<?php

declare(strict_types=1);

/**
 * Batch worker for personalized reading PDF delivery.
 *
 * Preferred: cPanel cron every minute, e.g.
 *   php /home/USER/path/to/scripts/deliver-readings.php --limit=5
 *
 * Fallback: external scheduler pings GET /api/run-delivery.php?key=SECRET
 */

use App\Application\ReadingDeliveryFactory;
use App\Config\AppConfig;
use App\Infrastructure\DatabaseConnection;
use App\Services\S3ReadingStorage;
use GuzzleHttp\Client;

$projectRoot = dirname(__DIR__);
require $projectRoot . '/vendor/autoload.php';

$config = AppConfig::load($projectRoot);
if (!$config->hasDatabaseConfig()) {
    fwrite(STDERR, "Database is not configured.\n");
    exit(1);
}

$storage = new S3ReadingStorage($config);
if (!$storage->isConfigured()) {
    fwrite(STDERR, "AWS S3 is not configured (AWS_ACCESS_KEY_ID, AWS_SECRET_ACCESS_KEY, AWS_S3_BUCKET).\n");
    exit(1);
}

$limit = 50;
foreach ($argv as $arg) {
    if (str_starts_with($arg, '--limit=')) {
        $parsed = (int) substr($arg, 8);
        if ($parsed > 0) {
            $limit = $parsed;
        }
    }
}

$pdo = DatabaseConnection::fromConfig($config);
$http = new Client($config->guzzleClientConfig());
$orchestrator = ReadingDeliveryFactory::orchestrator($config, $pdo, $http, $projectRoot);

$result = $orchestrator->processPendingBatch($limit);
if ($result['queued'] === 0) {
    echo "No purchases awaiting reading delivery.\n";
    exit(0);
}

echo 'Processing ' . $result['queued'] . " purchase(s)...\n";

$failedByPurchaseId = [];
foreach ($result['errors'] as $error) {
    $failedByPurchaseId[(int) ($error['purchase_id'] ?? 0)] = (string) ($error['message'] ?? 'unknown');
}

foreach ($result['purchase_ids'] as $purchaseId) {
    if (isset($failedByPurchaseId[$purchaseId])) {
        echo "  purchase {$purchaseId}: failed — {$failedByPurchaseId[$purchaseId]}\n";
        continue;
    }
    echo "  purchase {$purchaseId}: delivered\n";
}

echo "Done. success={$result['success']} failed={$result['failed']}\n";
exit($result['failed'] > 0 ? 1 : 0);
