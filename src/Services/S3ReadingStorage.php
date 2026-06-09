<?php

declare(strict_types=1);

namespace App\Services;

use App\Config\AppConfig;
use Aws\S3\S3Client;

/**
 * Private S3 storage for personalized reading PDFs.
 */
final class S3ReadingStorage
{
    private ?S3Client $client = null;

    public function __construct(private readonly AppConfig $config) {}

    public function isConfigured(): bool
    {
        return $this->config->awsAccessKeyId !== ''
            && $this->config->awsSecretAccessKey !== ''
            && $this->config->awsS3Bucket !== ''
            && $this->config->awsRegion !== '';
    }

    public function generateObjectKey(): string
    {
        $bytes = random_bytes(16);
        $bytes[6] = chr(ord($bytes[6]) & 0x0f | 0x40);
        $bytes[8] = chr(ord($bytes[8]) & 0x3f | 0x80);
        $hex = bin2hex($bytes);

        return 'readings/' . sprintf(
            '%s-%s-%s-%s-%s',
            substr($hex, 0, 8),
            substr($hex, 8, 4),
            substr($hex, 12, 4),
            substr($hex, 16, 4),
            substr($hex, 20, 12),
        ) . '.pdf';
    }

    public function uploadPdf(string $localPath, string $objectKey): void
    {
        if (!is_readable($localPath)) {
            throw new \RuntimeException('PDF file is not readable.');
        }

        $this->client()->putObject([
            'Bucket' => $this->config->awsS3Bucket,
            'Key' => $objectKey,
            'SourceFile' => $localPath,
            'ContentType' => 'application/pdf',
            'ACL' => 'private',
        ]);
    }

    public function createPresignedDownloadUrl(string $objectKey, int $ttlSeconds = 900): string
    {
        $cmd = $this->client()->getCommand('GetObject', [
            'Bucket' => $this->config->awsS3Bucket,
            'Key' => $objectKey,
        ]);
        $request = $this->client()->createPresignedRequest($cmd, '+' . (string) $ttlSeconds . ' seconds');

        return (string) $request->getUri();
    }

    private function client(): S3Client
    {
        if ($this->client !== null) {
            return $this->client;
        }

        $this->client = new S3Client([
            'version' => 'latest',
            'region' => $this->config->awsRegion,
            'credentials' => [
                'key' => $this->config->awsAccessKeyId,
                'secret' => $this->config->awsSecretAccessKey,
            ],
        ]);

        return $this->client;
    }
}
