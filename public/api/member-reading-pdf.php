<?php

declare(strict_types=1);

use App\Config\AppConfig;
use App\Infrastructure\DatabaseConnection;
use App\Repository\PurchaseRepository;
use App\Repository\ReadingDeliveryRepository;
use App\Services\S3ReadingStorage;

$projectRoot = dirname(__DIR__, 2);
require $projectRoot . '/vendor/autoload.php';

session_start();
$leadId = isset($_SESSION['member_lead_id']) ? (int) $_SESSION['member_lead_id'] : 0;
if ($leadId < 1) {
    http_response_code(401);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['error' => 'Unauthorized.']);
    exit;
}

$config = AppConfig::load($projectRoot);
if (!$config->hasDatabaseConfig()) {
    http_response_code(500);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['error' => 'Database not configured.']);
    exit;
}

try {
    $pdo = DatabaseConnection::fromConfig($config);
    $purchases = new PurchaseRepository($pdo);
    if (!$purchases->buyerHasAnyPurchase($leadId)) {
        http_response_code(403);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['error' => 'Forbidden.']);
        exit;
    }

    $deliveries = new ReadingDeliveryRepository($pdo);
    $delivery = $deliveries->findByLeadId($leadId);
    if ($delivery === null) {
        http_response_code(404);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['error' => 'Reading not ready yet.']);
        exit;
    }

    $s3Key = (string) ($delivery['s3_object_key'] ?? '');
    if ($s3Key === '') {
        http_response_code(404);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['error' => 'Reading file unavailable.']);
        exit;
    }

    $storage = new S3ReadingStorage($config);
    if (!$storage->isConfigured()) {
        http_response_code(503);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['error' => 'Storage not configured.']);
        exit;
    }

    $url = $storage->createPresignedDownloadUrl($s3Key, 900);
    header('Location: ' . $url, true, 302);
} catch (Throwable) {
    http_response_code(500);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['error' => 'Unable to serve reading.']);
}
