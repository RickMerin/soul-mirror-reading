<?php

declare(strict_types=1);

namespace App\Logging;

/**
 * Append-only audit log for received ClickBank INS events (one JSON line per event).
 *
 * Mirrors {@see PipelineLogger}'s write pattern: writes to
 * <project>/storage/logs/clickbank-ins.log (or SOUL_MIRROR_LOG_PATH's directory), creating the
 * directory if needed. Records both accepted events (type "event") and rejected ones
 * (type "rejected") so a dropped or undecryptable notification is never silent.
 *
 * Never writes secrets, IVs, or ciphertext. The buyer email and full payload are intentionally
 * omitted; only the receipt, transaction type, normalized status, and access window are stored.
 */
final class ClickBankInsLogger
{
    private readonly string $logPath;

    public function __construct(string $projectRoot)
    {
        $override = self::env('SOUL_MIRROR_LOG_PATH');
        $baseDir = $override !== ''
            ? dirname($override)
            : $projectRoot . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'logs';

        $this->logPath = $baseDir . DIRECTORY_SEPARATOR . 'clickbank-ins.log';
    }

    /**
     * Logs an accepted INS event.
     */
    public function logEvent(?string $transactionType, ?string $receipt, string $status, ?string $accessUntil): void
    {
        $this->write([
            'type' => 'event',
            'transactionType' => $transactionType,
            'receipt' => $receipt,
            'status' => $status,
            'accessUntil' => $accessUntil,
        ]);
    }

    /**
     * Logs a rejected INS event (decrypt failure, bad envelope, missing fields, etc.).
     */
    public function logRejected(string $reason, ?string $transactionType = null, ?string $receipt = null): void
    {
        $this->write([
            'type' => 'rejected',
            'reason' => $reason,
            'transactionType' => $transactionType,
            'receipt' => $receipt,
        ]);
    }

    /**
     * @param array<string, mixed> $fields
     */
    private function write(array $fields): void
    {
        $dir = dirname($this->logPath);
        if (!is_dir($dir)) {
            @mkdir($dir, 0775, true);
        }

        $record = array_merge(['timestamp' => date(\DateTimeInterface::ATOM)], $fields);
        try {
            $line = json_encode($record, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR) . PHP_EOL;
        } catch (\JsonException $e) {
            error_log('ClickBankInsLogger: JSON encode failed: ' . $e->getMessage());

            return;
        }

        $ok = @file_put_contents($this->logPath, $line, FILE_APPEND | LOCK_EX);
        if ($ok === false) {
            error_log('ClickBankInsLogger: cannot write to ' . $this->logPath);
        }
    }

    private static function env(string $key): string
    {
        $v = $_ENV[$key] ?? getenv($key);

        return is_string($v) ? trim($v) : '';
    }
}
