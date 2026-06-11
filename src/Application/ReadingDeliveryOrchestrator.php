<?php

declare(strict_types=1);

namespace App\Application;

use App\Config\AppConfig;
use App\Domain\MirrorBlockResolver;
use App\Domain\ReadingProductSkus;
use App\Domain\ReadingTemplatePersonalizer;
use App\Repository\LeadRepository;
use App\Repository\ReadingDeliveryRepository;
use App\Services\KitService;
use App\Services\ReadingPdfRenderer;
use App\Services\S3ReadingStorage;
use JsonException;
use Throwable;

/**
 * Generates, stores, and notifies buyers of their personalized reading PDF.
 */
final class ReadingDeliveryOrchestrator
{
    public function __construct(
        private readonly AppConfig $config,
        private readonly ReadingDeliveryRepository $deliveries,
        private readonly LeadRepository $leads,
        private readonly ReadingTemplatePersonalizer $personalizer,
        private readonly MirrorBlockResolver $mirrorBlocks,
        private readonly S3ReadingStorage $s3,
        private readonly ReadingPdfRenderer $pdfRenderer,
        private readonly KitService $kit,
        private readonly string $projectRoot,
    ) {}

    /**
     * @param array<string, mixed> $purchaseRow From {@see ReadingDeliveryRepository::findPurchasesAwaitingDelivery}
     */
    public function deliverForPurchaseRow(array $purchaseRow): bool
    {
        $purchaseId = (int) ($purchaseRow['purchase_id'] ?? 0);
        $leadId = (int) ($purchaseRow['lead_id'] ?? 0);
        if ($purchaseId < 1 || $leadId < 1) {
            return false;
        }

        if ($this->deliveries->hasCompletedDelivery($purchaseId)) {
            return true;
        }

        $this->deliveries->clearRetryableDelivery($purchaseId);

        $items = $this->decodeItemsJson($purchaseRow['items_json'] ?? null);
        if (!ReadingProductSkus::purchaseIncludesMainReading($items)) {
            return false;
        }

        $cards = $this->decodeCardsJson($purchaseRow['cards_json'] ?? null);
        if (count($cards) < 3) {
            $this->logDeliverySkip($purchaseId, 'lead missing three cards');

            return false;
        }

        $email = trim((string) ($purchaseRow['email'] ?? ''));
        if ($email === '') {
            return false;
        }

        $fullName = trim((string) ($purchaseRow['name'] ?? 'Member'));
        $firstName = explode(' ', $fullName)[0] ?? $fullName;
        $gender = trim((string) ($purchaseRow['gender'] ?? 'Female'));
        $mirrorBlockSlug = trim((string) ($purchaseRow['mirror_block_slug'] ?? ''));
        if ($mirrorBlockSlug === '') {
            $mirrorBlockSlug = $this->mirrorBlocks->resolveFromCards($cards);
            $this->leads->updateMirrorBlockSlug($leadId, $mirrorBlockSlug);
        }

        $wealth = $cards[2] ?? [];
        $love = $cards[0] ?? [];
        $life = $cards[1] ?? [];

        $s3Key = $this->s3->generateObjectKey();
        $this->deliveries->createPending($purchaseId, $leadId, $s3Key);

        $workDir = $this->projectRoot . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'pdf-work';
        if (!is_dir($workDir) && !mkdir($workDir, 0775, true) && !is_dir($workDir)) {
            throw new \RuntimeException('Unable to create PDF work directory.');
        }

        $filledHtmlPath = $workDir . DIRECTORY_SEPARATOR . 'reading-' . (string) $purchaseId . '.html';
        $outputPdfPath = $workDir . DIRECTORY_SEPARATOR . 'reading-' . (string) $purchaseId . '.pdf';

        try {
            $templatePath = $this->projectRoot
                . DIRECTORY_SEPARATOR . 'storage'
                . DIRECTORY_SEPARATOR . 'templates'
                . DIRECTORY_SEPARATOR . 'Soul-Mirror-Reading-FINAL.html';
            if (!is_readable($templatePath)) {
                throw new \RuntimeException('Reading template is missing.');
            }

            $templateHtml = (string) file_get_contents($templatePath);
            $filledHtml = $this->personalizer->personalize($templateHtml, [
                'firstName' => $firstName,
                'gender' => $gender,
                'wealthCardName' => (string) ($wealth['name'] ?? ''),
                'loveCardName' => (string) ($love['name'] ?? ''),
                'lifeCardName' => (string) ($life['name'] ?? ''),
                'wealthCardId' => $wealth['id'] ?? null,
                'loveCardId' => $love['id'] ?? null,
                'lifeCardId' => $life['id'] ?? null,
                'mirrorBlockSlug' => $mirrorBlockSlug,
            ]);

            if (file_put_contents($filledHtmlPath, $filledHtml) === false) {
                throw new \RuntimeException('Unable to write filled HTML.');
            }

            $this->pdfRenderer->renderHtmlFileToPdf($filledHtmlPath, $outputPdfPath);
            $this->s3->uploadPdf($outputPdfPath, $s3Key);
            $this->deliveries->markGenerated($purchaseId);

            try {
                $memberUrl = rtrim($this->config->appBaseUrl, '/') . '/member/';
                $this->kit->notifyReadingDelivered($email, $firstName, $memberUrl);
                $this->deliveries->markEmailed($purchaseId);
            } catch (Throwable $kitError) {
                error_log('reading_delivery: purchase=' . $purchaseId . ' kit_notify_fail ' . $kitError::class);
            }

            $this->logDeliverySuccess($purchaseId, $s3Key);

            return true;
        } catch (Throwable $e) {
            if (!$this->deliveries->hasCompletedDelivery($purchaseId)) {
                $this->deliveries->markFailed($purchaseId, $e->getMessage());
            }
            $this->logDeliveryFailure($purchaseId, $e);
            throw $e;
        } finally {
            foreach ([$filledHtmlPath ?? '', $outputPdfPath ?? ''] as $path) {
                if (is_string($path) && $path !== '' && is_file($path)) {
                    @unlink($path);
                }
            }
        }
    }

    public function deliverForPurchaseId(int $purchaseId): bool
    {
        $row = $this->deliveries->findPurchaseRowForDelivery($purchaseId);
        if ($row === null) {
            return false;
        }

        $status = strtolower(trim((string) ($row['status'] ?? '')));
        if (!in_array($status, ['approved', 'complete', 'completed', 'active'], true)) {
            return false;
        }

        return $this->deliverForPurchaseRow($row);
    }

    /**
     * Deliver up to $limit eligible pending purchases; returns run statistics (no PII).
     *
     * @return array{
     *     queued: int,
     *     success: int,
     *     failed: int,
     *     purchase_ids: list<int>,
     *     errors: list<array{purchase_id: int, message: string}>
     * }
     */
    public function processPendingBatch(int $limit): array
    {
        $pending = $this->findPendingPurchases($limit);
        $result = [
            'queued' => count($pending),
            'success' => 0,
            'failed' => 0,
            'purchase_ids' => [],
            'errors' => [],
        ];

        foreach ($pending as $row) {
            $purchaseId = (int) ($row['purchase_id'] ?? 0);
            $result['purchase_ids'][] = $purchaseId;
            try {
                $this->deliverForPurchaseRow($row);
                $result['success']++;
            } catch (Throwable $e) {
                $result['failed']++;
                $result['errors'][] = [
                    'purchase_id' => $purchaseId,
                    'message' => substr($e->getMessage(), 0, 200),
                ];
            }
        }

        return $result;
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function findPendingPurchases(int $limit = 50): array
    {
        $rows = $this->deliveries->findPurchasesAwaitingDelivery($limit);
        $pending = [];
        foreach ($rows as $row) {
            $items = $this->decodeItemsJson($row['items_json'] ?? null);
            if (!ReadingProductSkus::purchaseIncludesMainReading($items)) {
                continue;
            }
            $cards = $this->decodeCardsJson($row['cards_json'] ?? null);
            if (count($cards) < 3) {
                continue;
            }
            $pending[] = $row;
        }

        return $pending;
    }

    /**
     * @return list<array{id?: int|string, name?: string}>
     */
    private function decodeCardsJson(mixed $raw): array
    {
        if (!is_string($raw) || $raw === '') {
            return [];
        }
        try {
            $decoded = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            return [];
        }

        return is_array($decoded) ? $decoded : [];
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function decodeItemsJson(mixed $raw): array
    {
        if (!is_string($raw) || $raw === '') {
            return [];
        }
        try {
            $decoded = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            return [];
        }

        return is_array($decoded) ? $decoded : [];
    }

    private function logDeliverySuccess(int $purchaseId, string $s3Key): void
    {
        error_log(sprintf('reading_delivery: purchase=%d status=ok key=%s', $purchaseId, $s3Key));
    }

    private function logDeliverySkip(int $purchaseId, string $reason): void
    {
        error_log(sprintf('reading_delivery: purchase=%d status=skip reason=%s', $purchaseId, $reason));
    }

    private function logDeliveryFailure(int $purchaseId, Throwable $e): void
    {
        error_log(sprintf(
            'reading_delivery: purchase=%d status=fail error=%s',
            $purchaseId,
            substr($e->getMessage(), 0, 200),
        ));
    }
}
