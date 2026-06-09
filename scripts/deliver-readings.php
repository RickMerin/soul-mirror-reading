#!/usr/bin/env php
<?php

declare(strict_types=1);

use App\Application\ReadingDeliveryOrchestrator;
use App\Config\AppConfig;
use App\Domain\MirrorBlockResolver;
use App\Domain\ReadingTemplatePersonalizer;
use App\Domain\SoulMirrorCardImageUrlBuilder;
use App\Infrastructure\DatabaseConnection;
use App\Repository\LeadRepository;
use App\Repository\ReadingDeliveryRepository;
use App\Services\KitService;
use App\Services\ReadingPdfRenderer;
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
$orchestrator = new ReadingDeliveryOrchestrator(
    config: $config,
    deliveries: new ReadingDeliveryRepository($pdo),
    leads: new LeadRepository($pdo),
    personalizer: new ReadingTemplatePersonalizer(
        new SoulMirrorCardImageUrlBuilder($config->soulMirrorCardsBaseUrl),
    ),
    mirrorBlocks: new MirrorBlockResolver(),
    s3: $storage,
    pdfRenderer: new ReadingPdfRenderer($projectRoot),
    kit: new KitService($config, $http),
    projectRoot: $projectRoot,
);

$pending = $orchestrator->findPendingPurchases($limit);
if ($pending === []) {
    echo "No purchases awaiting reading delivery.\n";
    exit(0);
}

echo 'Processing ' . count($pending) . " purchase(s)...\n";
$ok = 0;
$fail = 0;

foreach ($pending as $row) {
    $purchaseId = (int) ($row['purchase_id'] ?? 0);
    try {
        $orchestrator->deliverForPurchaseRow($row);
        echo "  purchase {$purchaseId}: delivered\n";
        $ok++;
    } catch (Throwable $e) {
        echo "  purchase {$purchaseId}: failed — " . substr($e->getMessage(), 0, 120) . "\n";
        $fail++;
    }
}

echo "Done. success={$ok} failed={$fail}\n";
exit($fail > 0 ? 1 : 0);
