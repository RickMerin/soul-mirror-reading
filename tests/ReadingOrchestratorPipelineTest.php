<?php

declare(strict_types=1);

namespace App\Tests;

use App\Application\ReadingOrchestrator;
use App\Config\AppConfig;
use App\Domain\CardImageUrlBuilder;
use App\Domain\SunSignResolver;
use App\Logging\PipelineLogger;
use App\Services\AstrologyApiClient;
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
        return new AppConfig(
            astroUserId: 'astro-user',
            astroApiKey: 'astro-secret',
            kitApiKey: 'kit-secret',
            kitTagName: 'soul-mirror-leads',
            kitTagNameBuyer: 'soul-mirror-buyers',
            pipelineFileLog: true,
            pipelineLogPath: $logPath,
            sslCaBundlePath: '',
            dbHost: '127.0.0.1',
            dbPort: 3306,
            dbName: '',
            dbUser: '',
            dbPass: '',
            appBaseUrl: 'https://example.test',
            clickbankInsSlackWebhookUrl: '',
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
            new Response(200, [], json_encode(['tags' => [['id' => 7, 'name' => 'soul-mirror-leads']]], JSON_THROW_ON_ERROR)),
            new Response(200, [], '{}'),
        ]);
        $client = new Client(['handler' => HandlerStack::create($mock)]);

        $orchestrator = new ReadingOrchestrator(
            $config,
            new AstrologyApiClient($config, $client),
            new KitService($config, $client),
            new SunSignResolver(),
            new CardImageUrlBuilder(),
            new PipelineLogger($config),
            null,
        );

        $result = $orchestrator->run($this->validReadingBody());

        $this->assertSame(200, $result->httpStatus);
        $this->assertTrue($result->json['success'] ?? false);

        $this->assertFileExists($logPath);
        $log = (string) file_get_contents($logPath);
        $this->assertStringContainsString('kit: subscriber upsert ok', $log);
        $this->assertStringContainsString('kit: tag applied tag=soul-mirror-leads', $log);

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
            new AstrologyApiClient($config, $client),
            new KitService($config, $client),
            new SunSignResolver(),
            new CardImageUrlBuilder(),
            new PipelineLogger($config),
            null,
        );

        $result = $orchestrator->run($this->validReadingBody());

        $this->assertSame(500, $result->httpStatus);
        $log = (string) file_get_contents($logPath);
        $this->assertStringContainsString('kit: fail', $log);

        unlink($logPath);
    }
}
