<?php

declare(strict_types=1);

namespace App\Services;

/**
 * Invokes the Node + Puppeteer worker to render filled HTML as PDF.
 */
final class ReadingPdfRenderer
{
    public function __construct(
        private readonly string $projectRoot,
        private readonly string $nodeBinary = 'node',
    ) {}

    public function renderHtmlFileToPdf(string $htmlPath, string $outputPdfPath): void
    {
        if (!is_readable($htmlPath)) {
            throw new \RuntimeException('HTML input is not readable.');
        }

        $script = $this->projectRoot . DIRECTORY_SEPARATOR . 'pdf-worker' . DIRECTORY_SEPARATOR . 'render-pdf.mjs';
        if (!is_readable($script)) {
            throw new \RuntimeException('PDF worker script is missing.');
        }

        $cmd = escapeshellarg($this->nodeBinary)
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
}
