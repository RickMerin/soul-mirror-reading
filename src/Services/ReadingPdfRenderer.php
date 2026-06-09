<?php

declare(strict_types=1);

namespace App\Services;

use App\Config\AppConfig;
use GuzzleHttp\ClientInterface;

/**
 * Renders filled reading HTML to PDF via PDF Generator API (cPanel) or local Puppeteer.
 */
final class ReadingPdfRenderer
{
    public function __construct(
        private readonly AppConfig $config,
        private readonly string $projectRoot,
        private readonly ?ClientInterface $http = null,
        private readonly ?PdfGeneratorApiClient $pdfGeneratorApi = null,
    ) {}

    public function renderHtmlFileToPdf(string $htmlPath, string $outputPdfPath): void
    {
        if (!is_readable($htmlPath)) {
            throw new \RuntimeException('HTML input is not readable.');
        }

        $html = (string) file_get_contents($htmlPath);
        if ($html === '') {
            throw new \RuntimeException('HTML input is empty.');
        }

        if ($this->apiClient()->isConfigured()) {
            $this->renderViaPdfGeneratorApi($html, $outputPdfPath);

            return;
        }

        $this->renderViaPuppeteer($htmlPath, $outputPdfPath);
    }

    private function renderViaPdfGeneratorApi(string $html, string $outputPdfPath): void
    {
        $pdfBytes = $this->apiClient()->convertHtmlToPdfBytes($html);
        if (file_put_contents($outputPdfPath, $pdfBytes) === false) {
            throw new \RuntimeException('Unable to write PDF output file.');
        }
    }

    private function renderViaPuppeteer(string $htmlPath, string $outputPdfPath): void
    {
        $script = $this->projectRoot . DIRECTORY_SEPARATOR . 'pdf-worker' . DIRECTORY_SEPARATOR . 'render-pdf.mjs';
        if (!is_readable($script)) {
            throw new \RuntimeException('PDF worker script is missing and PDF Generator API is not configured.');
        }

        $node = (string) (getenv('NODE_BINARY') ?: 'node');
        $cmd = escapeshellarg($node)
            . ' ' . escapeshellarg($script)
            . ' --input ' . escapeshellarg($htmlPath)
            . ' --output ' . escapeshellarg($outputPdfPath);

        $descriptor = [0 => ['pipe', 'r'], 1 => ['pipe', 'w'], 2 => ['pipe', 'w']];
        $process = proc_open($cmd, $descriptor, $pipes, $this->projectRoot);
        if (!is_resource($process)) {
            throw new \RuntimeException('Unable to start PDF renderer process.');
        }

        fclose($pipes[0]);
        $stdout = stream_get_contents($pipes[1]) ?: '';
        $stderr = stream_get_contents($pipes[2]) ?: '';
        fclose($pipes[1]);
        fclose($pipes[2]);
        $exitCode = proc_close($process);

        if ($exitCode !== 0 || !is_readable($outputPdfPath)) {
            $detail = trim($stderr !== '' ? $stderr : $stdout);
            throw new \RuntimeException(
                'PDF render failed' . ($detail !== '' ? ': ' . substr($detail, 0, 500) : '.'),
            );
        }
    }

    private function apiClient(): PdfGeneratorApiClient
    {
        if ($this->pdfGeneratorApi !== null) {
            return $this->pdfGeneratorApi;
        }
        if ($this->http === null) {
            throw new \RuntimeException('HTTP client required for PDF Generator API rendering.');
        }

        return new PdfGeneratorApiClient($this->config, $this->http);
    }
}
