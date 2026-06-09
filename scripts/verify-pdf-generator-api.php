#!/usr/bin/env php
<?php

declare(strict_types=1);

/**
 * Smoke-test PDF Generator API HTML→PDF using credentials from .env (no secrets printed).
 *
 * Usage: php scripts/verify-pdf-generator-api.php
 */

use App\Config\AppConfig;
use App\Services\PdfGeneratorApiClient;
use GuzzleHttp\Client;

$projectRoot = dirname(__DIR__);
require $projectRoot . '/vendor/autoload.php';

$config = AppConfig::load($projectRoot);
$client = new PdfGeneratorApiClient($config, new Client($config->guzzleClientConfig()));

echo "Soul Mirror — PDF Generator API check\n";

if (!$client->isConfigured()) {
    fwrite(STDERR, "MISSING: set PDF_GENERATOR_API_KEY, PDF_GENERATOR_API_SECRET, PDF_GENERATOR_API_WORKSPACE in .env\n");
    exit(1);
}

echo 'Workspace configured: yes' . "\n";
echo 'Base URL: ' . ($config->pdfGeneratorApiBaseUrl !== ''
    ? $config->pdfGeneratorApiBaseUrl
    : 'https://us1.pdfgeneratorapi.com/api/v4') . "\n";

$html = '<!DOCTYPE html><html><head><style>body{font-family:serif;padding:40px;}h1{color:#C9A14A;}</style></head>'
    . '<body><h1>Soul Mirror API Test</h1><p>PDF Generator API connectivity check.</p></body></html>';

try {
    $pdf = $client->convertHtmlToPdfBytes($html);
    $isPdf = str_starts_with($pdf, '%PDF');
    echo 'PDF bytes: ' . strlen($pdf) . "\n";
    echo 'PDF magic: ' . ($isPdf ? 'yes' : 'no') . "\n";
    if (!$isPdf) {
        fwrite(STDERR, "Unexpected response — not a valid PDF.\n");
        exit(1);
    }
    echo "PDF Generator API: VERIFIED\n";
} catch (Throwable $e) {
    fwrite(STDERR, 'FAIL: ' . $e->getMessage() . "\n");
    exit(1);
}
