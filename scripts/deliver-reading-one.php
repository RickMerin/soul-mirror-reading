#!/usr/bin/env php
<?php

declare(strict_types=1);

/**
 * Generates one buyer's reading PDF (used by ClickBank INS / funnel triggers).
 *
 * Usage: php scripts/deliver-reading-one.php --purchase-id=123
 */

use App\Application\ReadingDeliveryFactory;
use App\Config\AppConfig;
use App\Infrastructure\DatabaseConnection;
use GuzzleHttp\Client;

$projectRoot = dirname(__DIR__);
require $projectRoot . '/vendor/autoload.php';

$purchaseId = 0;
foreach ($argv as $arg) {
    if (str_starts_with($arg, '--purchase-id=')) {
        $purchaseId = (int) substr($arg, 14);
    }
}

if ($purchaseId < 1) {
    fwrite(STDERR, "Usage: php scripts/deliver-reading-one.php --purchase-id=ID\n");
    exit(1);
}

$config = AppConfig::load($projectRoot);
if (!$config->hasDatabaseConfig()) {
    fwrite(STDERR, "Database is not configured.\n");
    exit(1);
}

$pdo = DatabaseConnection::fromConfig($config);
$http = new Client($config->guzzleClientConfig());
$orchestrator = ReadingDeliveryFactory::orchestrator($config, $pdo, $http, $projectRoot);

try {
    if ($orchestrator->deliverForPurchaseId($purchaseId)) {
        echo "purchase {$purchaseId}: delivered\n";
        exit(0);
    }
    echo "purchase {$purchaseId}: skipped (not eligible or already delivered)\n";
    exit(0);
} catch (Throwable $e) {
    fwrite(STDERR, "purchase {$purchaseId}: failed — " . $e->getMessage() . "\n");
    exit(1);
}
