<?php

declare(strict_types=1);

namespace App\Application;

use App\Config\AppConfig;
use App\Domain\MirrorBlockResolver;
use App\Domain\ReadingTemplatePersonalizer;
use App\Domain\SoulMirrorCardImageUrlBuilder;
use App\Repository\LeadRepository;
use App\Repository\ReadingDeliveryRepository;
use App\Services\KitService;
use App\Services\LocalTarotPredictionRepository;
use App\Services\ReadingPdfRenderer;
use App\Services\S3ReadingStorage;
use GuzzleHttp\ClientInterface;
use PDO;

/**
 * Wires {@see ReadingDeliveryOrchestrator} from app config and PDO.
 */
final class ReadingDeliveryFactory
{
    public static function orchestrator(
        AppConfig $config,
        PDO $pdo,
        ClientInterface $http,
        string $projectRoot,
    ): ReadingDeliveryOrchestrator {
        return new ReadingDeliveryOrchestrator(
            config: $config,
            deliveries: new ReadingDeliveryRepository($pdo),
            leads: new LeadRepository($pdo),
            personalizer: new ReadingTemplatePersonalizer(
                new SoulMirrorCardImageUrlBuilder($config->soulMirrorCardsBaseUrl),
                new LocalTarotPredictionRepository($config->tarotPredictionsPath()),
            ),
            mirrorBlocks: new MirrorBlockResolver(),
            s3: new S3ReadingStorage($config),
            pdfRenderer: new ReadingPdfRenderer($config, $projectRoot, $http),
            kit: new KitService($config, $http),
            projectRoot: $projectRoot,
        );
    }
}
