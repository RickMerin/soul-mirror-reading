<?php

declare(strict_types=1);

namespace App\Logging;

/**
 * Append-only audit log for received ClickBank INS events (one JSON line per event).
 *
 * Mirrors {@see PipelineLogger}'s write pattern: writes to
 * <project>/storage/logs/clickbank-ins.log (or SOUL_MIRROR_LOG_PATH's directory), creating the
 * directory if needed. Records received POSTs, parsed payloads, accepted events (type "event"),
 * and rejected ones (type "rejected") so a dropped or undecryptable notification is never silent.
 *
 * Never writes secrets, IVs, or ciphertext. The buyer email and full payload are intentionally
 * omitted; only the receipt, transaction type, normalized status, SKUs, and access window are stored.
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
     * Logs that a POST body was received (before decrypt).
     */
    public function logReceived(int $bytes): void
    {
        $this->write([
            'type' => 'received',
            'bytes' => $bytes,
        ]);
    }

    /**
     * Logs a successfully decrypted and validated payload summary (before persistence).
     *
     * @param list<string> $itemSkus
     */
    public function logParsed(
        ?string $transactionType,
        ?string $receipt,
        string $status,
        int $itemCount,
        array $itemSkus,
    ): void {
        $this->write([
            'type' => 'parsed',
            'transactionType' => $transactionType,
            'receipt' => $receipt,
            'status' => $status,
            'itemCount' => $itemCount,
            'itemSkus' => $itemSkus,
        ]);
    }

    /**
     * Logs an accepted INS event after persistence and revocation handling.
     *
     * @param list<string> $itemSkus
     */
    public function logProcessed(
        ?string $transactionType,
        ?string $receipt,
        string $status,
        int $leadId,
        ?int $purchaseId,
        int $revokedCount,
        string $revocationAction,
        bool $buyerRemoved,
        ?string $accessUntil,
        array $itemSkus = [],
    ): void {
        $this->write([
            'type' => 'event',
            'transactionType' => $transactionType,
            'receipt' => $receipt,
            'status' => $status,
            'leadId' => $leadId,
            'purchaseId' => $purchaseId,
            'revokedCount' => $revokedCount,
            'revocationAction' => $revocationAction,
            'buyerRemoved' => $buyerRemoved,
            'accessUntil' => $accessUntil,
            'itemSkus' => $itemSkus,
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
     * Logs a reconciliation action for one subscription (cron: reconcile-clickbank-subscriptions.php).
     * Receipt only, never the buyer email, matching this logger's no-PII policy.
     */
    public function logReconcile(
        string $receipt,
        string $apiStatus,
        string $action,
        string $reason,
        ?string $accessUntilBefore,
        ?string $accessUntilAfter,
        bool $fireRevoke,
    ): void {
        $this->write([
            'type' => 'reconcile',
            'receipt' => $receipt,
            'apiStatus' => $apiStatus,
            'action' => $action,
            'reason' => $reason,
            'accessUntilBefore' => $accessUntilBefore,
            'accessUntilAfter' => $accessUntilAfter,
            'fireRevoke' => $fireRevoke,
        ]);
    }

    /**
     * Logs a reconciliation run summary (totals) at the end of a cron pass.
     *
     * @param array<string, int> $totals
     */
    public function logReconcileSummary(int $scanned, array $totals): void
    {
        $this->write([
            'type' => 'reconcile_summary',
            'scanned' => $scanned,
            'totals' => $totals,
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
