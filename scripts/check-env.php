#!/usr/bin/env php
<?php

declare(strict_types=1);

/**
 * CLI: prints whether required env vars are set (never prints values).
 * Run from project root: php scripts/check-env.php
 */

$projectRoot = dirname(__DIR__);

require $projectRoot . '/vendor/autoload.php';

use App\Config\AppConfig;
use App\Logging\PipelineLogger;

$config = AppConfig::load($projectRoot);

$line = static function (string $name, bool $ok): void {
    echo $name . ': ' . ($ok ? 'set' : 'MISSING') . PHP_EOL;
};

echo 'Soul Mirror — env check (values are never shown)' . PHP_EOL;
echo 'Project root: ' . $projectRoot . PHP_EOL;
echo '.env file: ' . (is_readable($projectRoot . DIRECTORY_SEPARATOR . '.env') ? 'readable' : 'not found or not readable') . PHP_EOL;
echo PHP_EOL;

$line('ASTRO_USER_ID', $config->astroUserId !== '');
$line('ASTRO_API_KEY', $config->astroApiKey !== '');
$line('KIT_API_KEY', $config->kitApiKey !== '');
echo 'KIT_TAG_NAME (resolved): ' . $config->kitTagName . PHP_EOL;
echo 'SOUL_MIRROR_PIPELINE_LOG: ' . ($config->pipelineFileLog ? '1 (file log enabled)' : 'off') . PHP_EOL;
echo 'Pipeline log file: ' . $config->pipelineLogPath . PHP_EOL;
echo 'SSL CA bundle (Guzzle verify): ' . ($config->sslCaBundlePath !== '' ? $config->sslCaBundlePath : 'not set (fix cURL 60: put cacert.pem in storage/ or SSL_CAFILE=...)') . PHP_EOL;

if ($config->pipelineFileLog) {
    $pipelineLog = new PipelineLogger($config);
    $pipelineLog->line('check-env: pipeline log reachable (file created if it did not exist)');
    echo PHP_EOL . 'Appended a test line to the pipeline log. Open that file in your editor to confirm.' . PHP_EOL;
} else {
    echo PHP_EOL . 'No pipeline.log yet: add SOUL_MIRROR_PIPELINE_LOG=1 to .env, then run this script again.' . PHP_EOL;
}
