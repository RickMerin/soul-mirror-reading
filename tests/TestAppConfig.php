<?php

declare(strict_types=1);

namespace App\Tests;

use App\Config\AppConfig;

/**
 * Builds AppConfig for PHPUnit without loading .env.
 */
final class TestAppConfig
{
    public static function make(
        string $tarotSource = 'api',
        string $sunSource = 'api',
        ?string $dataDir = null,
        bool $pipelineFileLog = false,
        string $pipelineLogPath = '',
        string $kitFormSubscribeVia = 'api',
        string $kitFormUid = '87bff9e0cc',
        string $kitFormEmbedScript = '',
        string $kitFormEmbedUid = '',
    ): AppConfig {
        $projectRoot = \dirname(__DIR__);
        if ($dataDir === null) {
            $dataDir = $projectRoot . DIRECTORY_SEPARATOR . 'data';
        }

        return new AppConfig(
            astroUserId: 'astro-user',
            astroApiKey: 'astro-secret',
            tarotSource: $tarotSource,
            sunSource: $sunSource,
            dataDir: $dataDir,
            sunSignTimezone: 'UTC',
            kitApiKey: 'kit-secret',
            kitTagName: 'soul-mirror-leads',
            kitTagNameBuyer: 'soul-mirror-buyers',
            kitFormUid: $kitFormUid,
            kitFormEmbedScript: $kitFormEmbedScript,
            kitFormEmbedUid: $kitFormEmbedUid,
            kitFormSubscribeVia: $kitFormSubscribeVia,
            pipelineFileLog: $pipelineFileLog,
            pipelineLogPath: $pipelineLogPath,
            sslCaBundlePath: '',
            dbHost: '127.0.0.1',
            dbPort: 3306,
            dbName: '',
            dbUser: '',
            dbPass: '',
            appBaseUrl: 'https://example.test',
            clickbankInsSlackWebhookUrl: '',
            awsAccessKeyId: '',
            awsSecretAccessKey: '',
            awsRegion: 'us-east-1',
            awsS3Bucket: '',
            kitTagNameReadingDelivered: 'reading-delivered',
            soulMirrorCardsBaseUrl: 'https://soulmirrorreading.com/cards',
            pdfGeneratorApiKey: '',
            pdfGeneratorApiSecret: '',
            pdfGeneratorApiWorkspace: '',
            pdfGeneratorApiBaseUrl: '',
        );
    }
}
