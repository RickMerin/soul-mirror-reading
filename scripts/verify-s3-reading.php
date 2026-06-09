#!/usr/bin/env php
<?php

declare(strict_types=1);

/**
 * Smoke-test AWS S3 config for personalized reading delivery (no secrets printed).
 *
 * Usage: php scripts/verify-s3-reading.php
 */

use App\Config\AppConfig;
use App\Services\S3ReadingStorage;
use Aws\S3\S3Client;

$projectRoot = dirname(__DIR__);
require $projectRoot . '/vendor/autoload.php';

$config = AppConfig::load($projectRoot);
$storage = new S3ReadingStorage($config);

echo "Soul Mirror — S3 reading delivery check\n";

if (!$storage->isConfigured()) {
    fwrite(STDERR, "MISSING: set AWS_ACCESS_KEY_ID, AWS_SECRET_ACCESS_KEY, AWS_REGION, AWS_S3_BUCKET in .env\n");
    exit(1);
}

echo 'Region: ' . $config->awsRegion . "\n";
echo 'Bucket: ' . $config->awsS3Bucket . "\n";
echo 'CA bundle: ' . ($config->sslCaBundlePath !== '' ? $config->sslCaBundlePath : 'not set (may fail on Windows/WAMP)') . "\n";

$key = 'readings/_verify-' . bin2hex(random_bytes(4)) . '.txt';
$local = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'smr-s3-verify.txt';
file_put_contents($local, 'verify');

try {
    $storage->uploadPdf($local, $key);
    echo "PUT private object: OK\n";

    $publicUrl = 'https://' . $config->awsS3Bucket . '.s3.' . $config->awsRegion . '.amazonaws.com/' . $key;
    $ch = curl_init($publicUrl);
    curl_setopt_array($ch, [
        CURLOPT_NOBODY => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 15,
    ]);
    if ($config->sslCaBundlePath !== '') {
        curl_setopt($ch, CURLOPT_CAINFO, $config->sslCaBundlePath);
    }
    curl_exec($ch);
    $publicCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    echo 'Public URL blocked (expect 403): HTTP ' . $publicCode . "\n";

    $signed = $storage->createPresignedDownloadUrl($key, 120);
    $ch = curl_init($signed);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 15,
    ]);
    if ($config->sslCaBundlePath !== '') {
        curl_setopt($ch, CURLOPT_CAINFO, $config->sslCaBundlePath);
    }
    $body = curl_exec($ch);
    $signedCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    echo 'Presigned GET (expect 200): HTTP ' . $signedCode . "\n";

    $clientConfig = [
        'version' => 'latest',
        'region' => $config->awsRegion,
        'credentials' => [
            'key' => $config->awsAccessKeyId,
            'secret' => $config->awsSecretAccessKey,
        ],
    ];
    if ($config->sslCaBundlePath !== '') {
        $clientConfig['http'] = ['verify' => $config->sslCaBundlePath];
    }
    (new S3Client($clientConfig))->deleteObject([
        'Bucket' => $config->awsS3Bucket,
        'Key' => $key,
    ]);
    echo "Cleanup: OK\n";

    if ($publicCode !== 403 || $signedCode !== 200 || $body !== 'verify') {
        fwrite(STDERR, "Unexpected S3 behavior — review bucket policy and IAM permissions.\n");
        exit(1);
    }

    echo "S3 reading delivery: VERIFIED\n";
} catch (Throwable $e) {
    fwrite(STDERR, 'FAIL: ' . $e->getMessage() . "\n");
    exit(1);
} finally {
    @unlink($local);
}
