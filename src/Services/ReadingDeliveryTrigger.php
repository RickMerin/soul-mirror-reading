<?php

declare(strict_types=1);

namespace App\Services;

use App\Domain\ReadingProductSkus;

/**
 * Queues personalized PDF generation in a background PHP process (non-blocking for webhooks).
 */
final class ReadingDeliveryTrigger
{
    public function __construct(private readonly string $projectRoot) {}

    public function queuePurchaseDelivery(int $purchaseId): void
    {
        if ($purchaseId < 1) {
            return;
        }

        $php = PHP_BINARY;
        $script = $this->projectRoot . DIRECTORY_SEPARATOR . 'scripts' . DIRECTORY_SEPARATOR . 'deliver-reading-one.php';
        if (!is_readable($script)) {
            error_log('ReadingDeliveryTrigger: deliver-reading-one.php missing');

            return;
        }

        $cmd = escapeshellarg($php)
            . ' ' . escapeshellarg($script)
            . ' --purchase-id=' . (string) $purchaseId;

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            pclose(popen('start /B "" ' . $cmd, 'r'));

            return;
        }

        exec($cmd . ' > /dev/null 2>&1 &');
    }

    /**
     * @param array<int, array<string, mixed>> $lineItems
     */
    public static function shouldDeliverForApprovedPurchase(string $status, array $lineItems): bool
    {
        if (!in_array(strtolower(trim($status)), ['approved', 'complete', 'completed', 'active'], true)) {
            return false;
        }

        return ReadingProductSkus::purchaseIncludesMainReading($lineItems);
    }
}
