<?php

declare(strict_types=1);

namespace App\Config;

use Dotenv\Dotenv;

/**
 * Loads environment-driven settings for AstrologyAPI and Kit.
 *
 * Call {@see self::load()} once per request from the project root (directory
 * containing `composer.json` and optional `.env`).
 */
final class AppConfig
{
    public function __construct(
        public readonly string $astroUserId,
        public readonly string $astroApiKey,
        public readonly string $kitApiKey,
        public readonly string $kitTagName,
        /** When true, append pipeline diagnostics to {@see self::$pipelineLogPath} (no secrets or PII). */
        public readonly bool $pipelineFileLog,
        /** Absolute path to the pipeline log file. */
        public readonly string $pipelineLogPath,
    ) {}

    /**
     * Loads `.env` when present (safe, non-destructive) and builds config from `$_ENV` / `getenv`.
     */
    public static function load(string $projectRoot): self
    {
        $envPath = $projectRoot . DIRECTORY_SEPARATOR . '.env';
        if (is_readable($envPath)) {
            Dotenv::createImmutable($projectRoot)->safeLoad();
        }

        $get = static function (string $key): string {
            $v = $_ENV[$key] ?? getenv($key);
            return is_string($v) ? $v : '';
        };

        $pipelineFileLog = $get('SOUL_MIRROR_PIPELINE_LOG') === '1';
        $logPathOverride = $get('SOUL_MIRROR_LOG_PATH');
        $pipelineLogPath = $logPathOverride !== ''
            ? $logPathOverride
            : $projectRoot . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . 'pipeline.log';

        return new self(
            astroUserId: $get('ASTRO_USER_ID'),
            astroApiKey: $get('ASTRO_API_KEY'),
            kitApiKey: $get('KIT_API_KEY'),
            kitTagName: $get('KIT_TAG_NAME') !== '' ? $get('KIT_TAG_NAME') : 'soul-mirror-leads',
            pipelineFileLog: $pipelineFileLog,
            pipelineLogPath: $pipelineLogPath,
        );
    }
}
