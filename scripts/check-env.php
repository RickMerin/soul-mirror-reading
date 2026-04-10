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
