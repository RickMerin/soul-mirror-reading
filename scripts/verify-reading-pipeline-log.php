#!/usr/bin/env php
<?php

declare(strict_types=1);

/**
 * Verifies storage/logs/pipeline.log after a reading submission (with SOUL_MIRROR_PIPELINE_LOG=1).
 * Run from project root: php scripts/verify-reading-pipeline-log.php
 *
 * Does not submit the form; submit once in the browser, then run this script.
 */

$projectRoot = dirname(__DIR__);

require $projectRoot . '/vendor/autoload.php';

use App\Config\AppConfig;

$config = AppConfig::load($projectRoot);

echo 'Soul Mirror — reading pipeline log check' . PHP_EOL;
echo 'KIT_TAG_NAME (resolved): ' . $config->kitTagName . PHP_EOL;
echo PHP_EOL;

if (!$config->pipelineFileLog) {
    echo 'SOUL_MIRROR_PIPELINE_LOG is not 1 — enable it in .env, submit the unlock form once, then re-run this script.' . PHP_EOL;
    exit(2);
}

$path = $config->pipelineLogPath;
if (!is_readable($path)) {
    echo 'Pipeline log not found or not readable: ' . $path . PHP_EOL;
    echo 'Submit the unlock form after enabling SOUL_MIRROR_PIPELINE_LOG=1.' . PHP_EOL;
    exit(2);
}

$tail = static function (string $file, int $maxBytes = 65536): string {
    $size = filesize($file);
    if ($size === false || $size === 0) {
        return '';
    }
    $h = fopen($file, 'rb');
    if ($h === false) {
        return '';
    }
    $start = $size > $maxBytes ? $size - $maxBytes : 0;
    fseek($h, $start);
    $buf = stream_get_contents($h);
    fclose($h);

    return is_string($buf) ? $buf : '';
};

$content = $tail($path);
if ($content === '') {
    echo 'Pipeline log is empty. Submit a reading, then re-run.' . PHP_EOL;
    exit(2);
}

$hasUpsert = str_contains($content, 'kit: subscriber upsert ok');
$hasTag = str_contains($content, 'kit: tag applied tag=');
$hasComplete = str_contains($content, 'pipeline: complete HTTP 200');

echo 'Log file: ' . $path . PHP_EOL;
echo 'kit: subscriber upsert ok: ' . ($hasUpsert ? 'yes' : 'NO') . PHP_EOL;
echo 'kit: tag applied: ' . ($hasTag ? 'yes' : 'NO') . PHP_EOL;
echo 'pipeline: complete HTTP 200: ' . ($hasComplete ? 'yes' : 'NO') . PHP_EOL;
echo PHP_EOL;

echo '--- Kit dashboard (manual) ---' . PHP_EOL;
echo '1. Open Kit → Subscribers: confirm test email exists with custom fields populated.' . PHP_EOL;
echo '2. Confirm tag "' . $config->kitTagName . '" is on that subscriber.' . PHP_EOL;
echo '3. Automations: entry = "Tag added" / "' . $config->kitTagName . '", automation published, first step sends the report email.' . PHP_EOL;
echo '4. Email body: paste HTML from public/email-template.html (after npm run build); fix merge tags in Kit preview.' . PHP_EOL;

if (!$hasUpsert || !$hasTag) {
    echo PHP_EOL . 'FAIL: expected Kit success lines not found in recent log tail.' . PHP_EOL;
    exit(1);
}

echo PHP_EOL . 'OK: recent log contains Kit subscriber upsert and tag lines.' . PHP_EOL;
exit(0);
