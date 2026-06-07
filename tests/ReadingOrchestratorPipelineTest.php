<?php

declare(strict_types=1);

namespace App\Tests;

use App\Application\ReadingOrchestrator;
use App\Application\ReadingServiceFactory;
use App\Config\AppConfig;
use App\Domain\CardImageUrlBuilder;
use App\Domain\SunSignResolver;
use App\Logging\PipelineLogger;
use App\Services\KitService;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class ReadingOrchestratorPipelineTest extends TestCase
{
    private function pipelineAppConfig(string $logPath): AppConfig
    {
        return TestAppConfig::make(
            tarotSource: 'api',
            sunSource: 'api',
            pipelineFileLog: true,
            pipelineLogPath: $logPath,
        );
    }

    /**
     * @return array<int, array{key: string}>
     */
    private function allKitCustomFieldEntries(): array
    {
        $keys = [
            'date_of_birth', 'gender', 'love_card', 'life_card', 'wealth_card',
            'love_reading', 'life_reading', 'wealth_reading',
            'love_card_image', 'life_card_image', 'wealth_card_image',
            'sun_sign', 'sun_personal_life', 'sun_profession', 'sun_health',
            'sun_emotions', 'sun_travel', 'sun_luck',
        ];

        return array_map(static fn (string $k): array => ['key' => $k], $keys);
    }

    /**
     * @return array<string, mixed>
     */
    private function validReadingBody(): array
    {
        return [
            'name' => 'Jane',
            'email' => 'jane@example.com',
            'dob' => '03/25/1990',
            'gender' => 'Female',
            'card1' => 1,
            'card2' => 2,
            'card3' => 3,
            'card1Name' => 'The Fool',
            'card2Name' => 'The Magician',
            'card3Name' => 'The High Priestess',
        ];
    }

    public function testSuccessfulRunWritesKitTagAppliedToPipelineLog(): void
    {
        $logPath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'soul_mirror_pipeline_' . uniqid('', true) . '.log';
        if (is_file($logPath)) {
            unlink($logPath);
        }

        $config = $this->pipelineAppConfig($logPath);
        $mock = new MockHandler([
            new Response(200, [], json_encode(['love' => 'L', 'career' => 'C', 'finance' => 'F'], JSON_THROW_ON_ERROR)),
            new Response(200, [], json_encode(['prediction' => ['personal_life' => 'p']], JSON_THROW_ON_ERROR)),
            new Response(200, [], json_encode(['custom_fields' => $this->allKitCustomFieldEntries()], JSON_THROW_ON_ERROR)),
            new Response(200, [], '{"subscriber":{"id":1}}'),
            new Response(200, [], json_encode(['forms' => [['id' => 217, 'uid' => '87bff9e0cc']]], JSON_THROW_ON_ERROR)),
            new Response(201, [], '{"subscriber":{"id":1}}'),
            new Response(200, [], json_encode(['tags' => [['id' => 7, 'name' => 'soul-mirror-leads']]], JSON_THROW_ON_ERROR)),
            new Response(200, [], '{}'),
        ]);
        $client = new Client(['handler' => HandlerStack::create($mock)]);

        $orchestrator = new ReadingOrchestrator(
            $config,
            ReadingServiceFactory::tarotProvider($config, $client),
            ReadingServiceFactory::sunSignProvider($config, $client),
            new KitService($config, $client),
            new SunSignResolver(),
            new CardImageUrlBuilder(),
            new PipelineLogger($config),
            null,
        );

        $result = $orchestrator->run($this->validReadingBody());

        $this->assertSame(200, $result->httpStatus);
        $this->assertTrue($result->json['success'] ?? false);
        $kitEmbedFields = $result->json['kitEmbedFields'] ?? null;
        $this->assertIsArray($kitEmbedFields);
        $this->assertSame('Jane', $kitEmbedFields['fields[first_name]']);
        $this->assertSame('The Fool', $kitEmbedFields['fields[love_card]']);
        $this->assertSame('L', $kitEmbedFields['fields[love_reading]']);
        $this->assertSame('aries', $kitEmbedFields['fields[sun_sign]']);

        $this->assertFileExists($logPath);
        $log = (string) file_get_contents($logPath);
        $this->assertStringContainsString('kit: subscriber upsert ok (readings on custom fields)', $log);
        $this->assertStringContainsString('kit: form_subscribe ok uid=87bff9e0cc', $log);
        $this->assertStringContainsString('kit: tag applied tag=soul-mirror-leads', $log);

        unlink($logPath);
    }

    public function testFormSubscribeSkippedWhenStrategyIsEmbed(): void
    {
        $logPath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'soul_mirror_pipeline_embed_' . uniqid('', true) . '.log';
        if (is_file($logPath)) {
            unlink($logPath);
        }

        $config = TestAppConfig::make(
            tarotSource: 'api',
            sunSource: 'api',
            pipelineFileLog: true,
            pipelineLogPath: $logPath,
            kitFormSubscribeVia: 'embed',
            kitFormEmbedScript: 'https://example.kit.com/87bff9e0cc/index.js',
            kitFormEmbedUid: '87bff9e0cc',
        );
        $mock = new MockHandler([
            new Response(200, [], json_encode(['love' => 'L', 'career' => 'C', 'finance' => 'F'], JSON_THROW_ON_ERROR)),
            new Response(200, [], json_encode(['prediction' => ['personal_life' => 'p']], JSON_THROW_ON_ERROR)),
            new Response(200, [], json_encode(['custom_fields' => $this->allKitCustomFieldEntries()], JSON_THROW_ON_ERROR)),
            new Response(200, [], '{"subscriber":{"id":1}}'),
            new Response(200, [], json_encode(['tags' => [['id' => 7, 'name' => 'soul-mirror-leads']]], JSON_THROW_ON_ERROR)),
            new Response(200, [], '{}'),
        ]);
        $client = new Client(['handler' => HandlerStack::create($mock)]);

        $orchestrator = new ReadingOrchestrator(
            $config,
            ReadingServiceFactory::tarotProvider($config, $client),
            ReadingServiceFactory::sunSignProvider($config, $client),
            new KitService($config, $client),
            new SunSignResolver(),
            new CardImageUrlBuilder(),
            new PipelineLogger($config),
            null,
        );

        $result = $orchestrator->run($this->validReadingBody());

        $this->assertSame(200, $result->httpStatus);
        $kitEmbedFields = $result->json['kitEmbedFields'] ?? null;
        $this->assertIsArray($kitEmbedFields);
        $this->assertSame('The Fool', $kitEmbedFields['fields[love_card]']);
        $this->assertSame('L', $kitEmbedFields['fields[love_reading]']);
        $log = (string) file_get_contents($logPath);
        $this->assertStringContainsString('kit: subscriber upsert ok (readings on custom fields)', $log);
        $this->assertStringContainsString('kit: embed handoff', $log);
        $this->assertStringContainsString('kit: tag applied tag=soul-mirror-leads', $log);
        $this->assertStringNotContainsString('kit: form_subscribe ok', $log);

        unlink($logPath);
    }

    public function testKitFailureWritesKitFailToPipelineLog(): void
    {
        $logPath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'soul_mirror_pipeline_fail_' . uniqid('', true) . '.log';
        if (is_file($logPath)) {
            unlink($logPath);
        }

        $config = $this->pipelineAppConfig($logPath);
        $mock = new MockHandler([
            new Response(200, [], json_encode(['love' => 'L', 'career' => 'C', 'finance' => 'F'], JSON_THROW_ON_ERROR)),
            new Response(200, [], json_encode(['prediction' => []], JSON_THROW_ON_ERROR)),
            new Response(200, [], json_encode(['custom_fields' => $this->allKitCustomFieldEntries()], JSON_THROW_ON_ERROR)),
            new Response(401, [], json_encode(['errors' => 'Unauthorized'], JSON_THROW_ON_ERROR)),
        ]);
        $client = new Client(['handler' => HandlerStack::create($mock)]);

        $orchestrator = new ReadingOrchestrator(
            $config,
            ReadingServiceFactory::tarotProvider($config, $client),
            ReadingServiceFactory::sunSignProvider($config, $client),
            new KitService($config, $client),
            new SunSignResolver(),
            new CardImageUrlBuilder(),
            new PipelineLogger($config),
            null,
        );

        $result = $orchestrator->run($this->validReadingBody());

        // Kit failures are non-fatal: the reading still returns 200 and the lead is kept.
        $this->assertSame(200, $result->httpStatus);
        $log = (string) file_get_contents($logPath);
        $this->assertStringContainsString('kit: fail', $log);

        unlink($logPath);
    }

    public function testSuccessfulRunWithLocalTarotAndSunWithoutAstroHttp(): void
    {
        $configNoAstro = new AppConfig(
            astroUserId: '',
            astroApiKey: '',
            tarotSource: 'local',
            sunSource: 'local',
            dataDir: \dirname(__DIR__) . DIRECTORY_SEPARATOR . 'data',
            sunSignTimezone: 'UTC',
            kitApiKey: 'kit-secret',
            kitTagName: 'soul-mirror-leads',
            kitTagNameBuyer: 'soul-mirror-buyers',
            kitFormUid: '87bff9e0cc',
            kitFormEmbedScript: '',
            kitFormEmbedUid: '',
            kitFormSubscribeVia: 'none',
            pipelineFileLog: false,
            pipelineLogPath: '',
            sslCaBundlePath: '',
            dbHost: '127.0.0.1',
            dbPort: 3306,
            dbName: '',
            dbUser: '',
            dbPass: '',
            appBaseUrl: 'https://example.test',
            clickbankInsSlackWebhookUrl: '',
        );

        $client = new Client(['handler' => HandlerStack::create(new MockHandler([]))]);

        $orchestrator = new ReadingOrchestrator(
            $configNoAstro,
            ReadingServiceFactory::tarotProvider($configNoAstro, $client),
            ReadingServiceFactory::sunSignProvider($configNoAstro, $client),
            new KitService($configNoAstro, $client),
            new SunSignResolver(),
            new CardImageUrlBuilder(),
            new PipelineLogger($configNoAstro),
            null,
        );

        $fixtureSun = \dirname(__DIR__) . '/data/sun-sign/2026-05-19.json';
        if (!is_readable($fixtureSun)) {
            $this->markTestSkipped('Sun fixture missing for local orchestrator test.');
        }
        $sunDir = $configNoAstro->sunSignDataDir();
        if (!is_dir($sunDir)) {
            mkdir($sunDir, 0755, true);
        }
        $todayUtc = (new \DateTimeImmutable('now', new \DateTimeZone('UTC')))->format('Y-m-d');
        $todayFile = $sunDir . DIRECTORY_SEPARATOR . $todayUtc . '.json';
        if (!is_readable($todayFile)) {
            copy($fixtureSun, $todayFile);
        }

        $result = $orchestrator->run($this->validReadingBody());

        $this->assertSame(200, $result->httpStatus);
        $kitEmbedFields = $result->json['kitEmbedFields'] ?? null;
        $this->assertIsArray($kitEmbedFields);
        $this->assertNotSame('', trim($kitEmbedFields['fields[love_reading]'] ?? ''));
        $this->assertSame('aries', $kitEmbedFields['fields[sun_sign]'] ?? '');
        $this->assertNotSame('', trim($kitEmbedFields['fields[sun_personal_life]'] ?? ''));
    }
}
