<?php

declare(strict_types=1);

namespace App\Services;

use App\Config\AppConfig;
use App\Domain\PdfGeneratorApiJwt;
use GuzzleHttp\ClientInterface;

/**
 * PDF Generator API v4 — HTML to PDF conversion.
 *
 * @see https://us1.pdfgeneratorapi.com/api/v4/conversion/html2pdf
 */
final class PdfGeneratorApiClient
{
    private const DEFAULT_BASE = 'https://us1.pdfgeneratorapi.com/api/v4';

    public function __construct(
        private readonly AppConfig $config,
        private readonly ClientInterface $http,
        private readonly PdfGeneratorApiJwt $jwt = new PdfGeneratorApiJwt(),
    ) {}

    public function isConfigured(): bool
    {
        return $this->config->pdfGeneratorApiKey !== ''
            && $this->config->pdfGeneratorApiSecret !== ''
            && $this->config->pdfGeneratorApiWorkspace !== '';
    }

    public function convertHtmlToPdfBytes(string $html): string
    {
        if (!$this->isConfigured()) {
            throw new \RuntimeException('PDF Generator API is not configured.');
        }

        $token = $this->jwt->createToken(
            $this->config->pdfGeneratorApiKey,
            $this->config->pdfGeneratorApiSecret,
            $this->config->pdfGeneratorApiWorkspace,
        );

        $base = rtrim($this->config->pdfGeneratorApiBaseUrl !== ''
            ? $this->config->pdfGeneratorApiBaseUrl
            : self::DEFAULT_BASE, '/');

        $options = [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'json' => [
                'paper_size' => 'a4',
                'landscape' => false,
                'filename' => 'soul-mirror-reading',
                'content' => $html,
                'output' => 'base64',
            ],
            'http_errors' => false,
            'timeout' => 180.0,
        ];
        if ($this->config->sslCaBundlePath !== '') {
            $options['verify'] = $this->config->sslCaBundlePath;
        }

        $response = $this->http->request('POST', $base . '/conversion/html2pdf', $options);
        $status = $response->getStatusCode();
        $raw = $response->getBody()->getContents();

        if ($status < 200 || $status >= 300) {
            throw new PdfGeneratorApiException($status, self::summarizeErrorBody($raw));
        }

        try {
            /** @var mixed $decoded */
            $decoded = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw new PdfGeneratorApiException($status, 'Invalid JSON response from PDF Generator API.');
        }

        if (!is_array($decoded)) {
            throw new PdfGeneratorApiException($status, 'Unexpected PDF Generator API response.');
        }

        $encoded = $decoded['response'] ?? null;
        if (!is_string($encoded) || $encoded === '') {
            throw new PdfGeneratorApiException($status, 'PDF Generator API response missing base64 document.');
        }

        $pdf = base64_decode($encoded, true);
        if ($pdf === false || $pdf === '') {
            throw new PdfGeneratorApiException($status, 'Unable to decode PDF bytes from PDF Generator API.');
        }

        return $pdf;
    }

    private static function summarizeErrorBody(string $raw): string
    {
        if ($raw === '') {
            return 'Empty error response';
        }
        try {
            /** @var mixed $data */
            $data = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
            if (is_array($data)) {
                foreach (['message', 'error', 'errors'] as $key) {
                    if (isset($data[$key]) && is_string($data[$key]) && $data[$key] !== '') {
                        return substr($data[$key], 0, 300);
                    }
                }
            }
        } catch (\JsonException) {
            /* fall through */
        }

        return substr(preg_replace('/\s+/', ' ', $raw) ?? $raw, 0, 300);
    }
}

final class PdfGeneratorApiException extends \RuntimeException
{
    public function __construct(
        public readonly int $httpStatus,
        string $message,
    ) {
        parent::__construct('PDF Generator API HTTP ' . (string) $httpStatus . ': ' . $message);
    }
}
