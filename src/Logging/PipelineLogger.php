<?php

declare(strict_types=1);

namespace App\Logging;

use App\Config\AppConfig;

/**
 * Opt-in structured log for the reading pipeline (set SOUL_MIRROR_PIPELINE_LOG=1 in .env).
 *
 * Does not write secrets, tokens, emails, or DOB — only step outcomes and env "set/missing".
 */
final class PipelineLogger
{
    public function __construct(private readonly AppConfig $config) {}

    /**
     * Logs whether each credential env var is present (values are never written).
     */
    public function envSummary(): void
    {
        $this->line(sprintf(
            'env: ASTRO_USER_ID=%s ASTRO_API_KEY=%s KIT_API_KEY=%s kit_tag=%s',
            $this->config->astroUserId !== '' ? 'set' : 'missing',
            $this->config->astroApiKey !== '' ? 'set' : 'missing',
            $this->config->kitApiKey !== '' ? 'set' : 'missing',
            $this->config->kitTagName,
        ));
    }

    public function line(string $message): void
    {
        if (!$this->config->pipelineFileLog) {
            return;
        }

        $dir = dirname($this->config->pipelineLogPath);
        if (!is_dir($dir)) {
            @mkdir($dir, 0775, true);
        }

        $line = date('Y-m-d H:i:s') . ' ' . $message . PHP_EOL;
        $ok = @file_put_contents($this->config->pipelineLogPath, $line, FILE_APPEND | LOCK_EX);
        if ($ok === false) {
            error_log('PipelineLogger: cannot write to ' . $this->config->pipelineLogPath);
        }
    }
}
