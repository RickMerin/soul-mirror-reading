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
        /**
         * Path to a CA bundle for TLS verification (Guzzle `verify`).
         * Empty string = Guzzle default (often fails on Windows without php.ini `curl.cainfo`).
         */
        public readonly string $sslCaBundlePath,
    ) {}

    /**
     * Default Guzzle options for outbound API calls (AstrologyAPI, Kit).
     *
     * @return array<string, mixed>
     */
    public function guzzleClientConfig(): array
    {
        $c = [
            'timeout' => 45.0,
            'http_errors' => false,
        ];
        if ($this->sslCaBundlePath !== '') {
            $c['verify'] = $this->sslCaBundlePath;
        }

        return $c;
    }

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

        $sslCaBundlePath = self::resolveSslCaBundlePath($projectRoot, $get);

        return new self(
            astroUserId: $get('ASTRO_USER_ID'),
            astroApiKey: $get('ASTRO_API_KEY'),
            kitApiKey: $get('KIT_API_KEY'),
            kitTagName: $get('KIT_TAG_NAME') !== '' ? $get('KIT_TAG_NAME') : 'soul-mirror-leads',
            pipelineFileLog: $pipelineFileLog,
            pipelineLogPath: $pipelineLogPath,
            sslCaBundlePath: $sslCaBundlePath,
        );
    }

    /**
     * @param callable(string): string $get
     */
    private static function resolveSslCaBundlePath(string $projectRoot, callable $get): string
    {
        $fromEnv = $get('SSL_CAFILE');
        if ($fromEnv !== '') {
            $rp = realpath($fromEnv);
            if ($rp !== false && is_readable($rp)) {
                return $rp;
            }
        }

        $local = $projectRoot . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'cacert.pem';
        if (is_readable($local)) {
            $rp = realpath($local);

            return $rp !== false ? $rp : '';
        }

        return '';
    }
}
