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
        /** local = data/tarot-predictions.json; api = live AstrologyAPI */
        public readonly string $tarotSource,
        /** local = data/sun-sign/YYYY-MM-DD.json; api = live AstrologyAPI */
        public readonly string $sunSource,
        public readonly string $dataDir,
        public readonly string $sunSignTimezone,
        public readonly string $kitApiKey,
        public readonly string $kitTagName,
        /** Tag when ClickBank INS has no line-item SKUs; empty env falls back to {@see $kitTagName}. */
        public readonly string $kitTagNameBuyer,
        /** Kit form UID used to subscribe unlock-reading leads to a form automation. */
        public readonly string $kitFormUid,
        /** Full `src` URL from Kit’s JavaScript embed snippet (never an API secret). */
        public readonly string $kitFormEmbedScript,
        /** Optional `data-uid` from the same snippet; required by some Kit loaders. */
        public readonly string $kitFormEmbedUid,
        /** How unlock-reading attaches the lead to the Kit form: api (REST), embed (browser), or none. */
        public readonly string $kitFormSubscribeVia,
        /** When true, append pipeline diagnostics to {@see self::$pipelineLogPath} (no secrets or PII). */
        public readonly bool $pipelineFileLog,
        /** Absolute path to the pipeline log file. */
        public readonly string $pipelineLogPath,
        /**
         * Path to a CA bundle for TLS verification (Guzzle `verify`).
         * Empty string = Guzzle default (often fails on Windows without php.ini `curl.cainfo`).
         */
        public readonly string $sslCaBundlePath,
        public readonly string $dbHost,
        public readonly int $dbPort,
        public readonly string $dbName,
        public readonly string $dbUser,
        public readonly string $dbPass,
        public readonly string $appBaseUrl,
        /** Slack Incoming Webhook URL for ClickBank INS logs; empty = disabled. */
        public readonly string $clickbankInsSlackWebhookUrl,
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

        $kitTagName = $get('KIT_TAG_NAME') !== '' ? $get('KIT_TAG_NAME') : 'soul-mirror-leads';
        $kitTagNameBuyer = $get('KIT_TAG_NAME_BUYER') !== '' ? $get('KIT_TAG_NAME_BUYER') : $kitTagName;

        $kitSubscribeRaw = strtolower(trim($get('KIT_FORM_SUBSCRIBE_VIA')));
        $kitFormSubscribeVia = in_array($kitSubscribeRaw, ['api', 'embed', 'none'], true)
            ? $kitSubscribeRaw
            : 'api';

        $kitFormUid = trim($get('KIT_FORM_UID'));
        if ($kitFormUid === '') {
            $kitFormUid = trim($get('KIT_FORM_EMBED_UID'));
        }

        $dataDir = $get('DATA_DIR');
        if ($dataDir === '') {
            $dataDir = $projectRoot . DIRECTORY_SEPARATOR . 'data';
        } elseif (!str_contains($dataDir, ':\\') && !str_starts_with($dataDir, '/')) {
            $dataDir = $projectRoot . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $dataDir);
        }

        $tarotSource = strtolower(trim($get('TAROT_SOURCE')));
        if (!in_array($tarotSource, ['local', 'api'], true)) {
            $tarotSource = 'local';
        }

        $sunSource = strtolower(trim($get('SUN_SOURCE')));
        if (!in_array($sunSource, ['local', 'api'], true)) {
            $sunSource = 'local';
        }

        $sunTz = trim($get('SUN_SIGN_TIMEZONE'));
        if ($sunTz === '') {
            $sunTz = 'UTC';
        }

        return new self(
            astroUserId: $get('ASTRO_USER_ID'),
            astroApiKey: $get('ASTRO_API_KEY'),
            tarotSource: $tarotSource,
            sunSource: $sunSource,
            dataDir: $dataDir,
            sunSignTimezone: $sunTz,
            kitApiKey: $get('KIT_API_KEY'),
            kitTagName: $kitTagName,
            kitTagNameBuyer: $kitTagNameBuyer,
            kitFormUid: $kitFormUid,
            kitFormEmbedScript: $get('KIT_FORM_EMBED_SCRIPT'),
            kitFormEmbedUid: $get('KIT_FORM_EMBED_UID'),
            kitFormSubscribeVia: $kitFormSubscribeVia,
            pipelineFileLog: $pipelineFileLog,
            pipelineLogPath: $pipelineLogPath,
            sslCaBundlePath: $sslCaBundlePath,
            dbHost: $get('DB_HOST') !== '' ? $get('DB_HOST') : '127.0.0.1',
            dbPort: self::toIntOrDefault($get('DB_PORT'), 3306),
            dbName: $get('DB_NAME'),
            dbUser: $get('DB_USER'),
            dbPass: $get('DB_PASS'),
            appBaseUrl: rtrim($get('APP_BASE_URL'), '/'),
            clickbankInsSlackWebhookUrl: $get('CLICKBANK_INS_SLACK_WEBHOOK_URL'),
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

    public function hasDatabaseConfig(): bool
    {
        return $this->dbName !== '' && $this->dbUser !== '';
    }

    public function needsAstroApiCredentials(): bool
    {
        return $this->tarotSource === 'api' || $this->sunSource === 'api';
    }

    public function tarotPredictionsPath(): string
    {
        return $this->dataDir . DIRECTORY_SEPARATOR . 'tarot-predictions.json';
    }

    public function sunSignDataDir(): string
    {
        return $this->dataDir . DIRECTORY_SEPARATOR . 'sun-sign';
    }

    /**
     * Public bootstrap for unlock-reading (no secrets — safe for inline JSON).
     *
     * @return array{embedScriptSrc: string, embedDataUid: string, formSubscribeVia: string}
     */
    public function unlockReadingKitBootstrap(): array
    {
        return [
            'embedScriptSrc' => $this->kitFormEmbedScript,
            'embedDataUid' => $this->kitFormEmbedUid,
            'formSubscribeVia' => $this->kitFormSubscribeVia,
        ];
    }

    private static function toIntOrDefault(string $value, int $default): int
    {
        $filtered = filter_var($value, FILTER_VALIDATE_INT);

        return is_int($filtered) ? $filtered : $default;
    }
}
