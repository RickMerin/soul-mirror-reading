<?php

declare(strict_types=1);

namespace App\Tests;

use App\Config\AppConfig;
use App\Services\KitApiException;
use App\Services\KitService;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class KitServiceTest extends TestCase
{
    /**
     * @return array<int, array{key: string}>
     */
    private function allRequiredCustomFieldEntries(): array
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

    private function kitConfig(): AppConfig
    {
        return new AppConfig(
            astroUserId: '',
            astroApiKey: '',
            kitApiKey: 'test-kit-api-key',
            kitTagName: 'soul-mirror-leads',
            kitTagNameBuyer: 'soul-mirror-buyers',
            kitFormUid: '87bff9e0cc',
            kitFormEmbedScript: '',
            kitFormEmbedUid: '',
            kitFormSubscribeVia: 'api',
            pipelineFileLog: false,
            pipelineLogPath: '',
            sslCaBundlePath: '',
            dbHost: '127.0.0.1',
            dbPort: 3306,
            dbName: '',
            dbUser: '',
            dbPass: '',
            appBaseUrl: '',
            clickbankInsSlackWebhookUrl: '',
        );
    }

    public function testEnsureCustomFieldsAndUpsertSubscriberSucceedOn200(): void
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode(['custom_fields' => $this->allRequiredCustomFieldEntries()], JSON_THROW_ON_ERROR)),
            new Response(200, [], '{"subscriber":{"id":1}}'),
        ]);
        $client = new Client(['handler' => HandlerStack::create($mock)]);
        $kit = new KitService($this->kitConfig(), $client);

        $kit->ensureCustomFields();
        $kit->upsertSubscriber([
            'name' => 'Jane',
            'email' => 'vowabi3627@inraud.com',
            'dob' => '03/25/1990',
            'gender' => 'Female',
            'card1Name' => 'The Fool',
            'card2Name' => 'The Magician',
            'card3Name' => 'The High Priestess',
            'loveReading' => 'L',
            'lifeReading' => 'C',
            'wealthReading' => 'F',
            'loveCardImage' => 'https://example.com/1.png',
            'lifeCardImage' => 'https://example.com/2.png',
            'wealthCardImage' => 'https://example.com/3.png',
            'sunSign' => 'aries',
            'sunPersonalLife' => '',
            'sunProfession' => '',
            'sunHealth' => '',
            'sunEmotions' => '',
            'sunTravel' => '',
            'sunLuck' => '',
        ]);

        $this->assertSame(0, $mock->count());
    }

    public function testUpsertSubscriberThrowsKitApiExceptionOn401(): void
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode(['custom_fields' => $this->allRequiredCustomFieldEntries()], JSON_THROW_ON_ERROR)),
            new Response(401, [], json_encode(['errors' => 'Unauthorized'], JSON_THROW_ON_ERROR)),
        ]);
        $client = new Client(['handler' => HandlerStack::create($mock)]);
        $kit = new KitService($this->kitConfig(), $client);

        $kit->ensureCustomFields();
        try {
            $kit->upsertSubscriber([
                'name' => 'Jane',
                'email' => 'vowabi3627@inraud.com',
                'dob' => '03/25/1990',
                'gender' => 'Female',
                'card1Name' => 'The Fool',
                'card2Name' => 'The Magician',
                'card3Name' => 'The High Priestess',
                'loveReading' => 'L',
                'lifeReading' => 'C',
                'wealthReading' => 'F',
                'loveCardImage' => 'https://example.com/1.png',
                'lifeCardImage' => 'https://example.com/2.png',
                'wealthCardImage' => 'https://example.com/3.png',
                'sunSign' => '',
                'sunPersonalLife' => '',
                'sunProfession' => '',
                'sunHealth' => '',
                'sunEmotions' => '',
                'sunTravel' => '',
                'sunLuck' => '',
            ]);
            $this->fail('Expected KitApiException');
        } catch (KitApiException $e) {
            $this->assertSame(401, $e->statusCode);
            $this->assertStringContainsString('401', $e->getMessage());
            $this->assertStringContainsString('Unauthorized', $e->getMessage());
        }
    }

    public function testTagSubscriberThrowsWhenAddToTagReturns422(): void
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode(['subscriber' => ['id' => 7]], JSON_THROW_ON_ERROR)),
            new Response(200, [], json_encode([
                'tags' => [['id' => 42, 'name' => 'soul-mirror-leads']],
            ], JSON_THROW_ON_ERROR)),
            new Response(422, [], json_encode(['errors' => ['Email invalid']], JSON_THROW_ON_ERROR)),
        ]);
        $client = new Client(['handler' => HandlerStack::create($mock)]);
        $kit = new KitService($this->kitConfig(), $client);

        $this->expectException(KitApiException::class);
        $this->expectExceptionMessage('422');
        $kit->tagSubscriber('bad@', 'soul-mirror-leads');
    }

    public function testSubscribeLeadToConfiguredFormPostsToKitFormEndpoint(): void
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode(['subscription' => ['id' => 99]], JSON_THROW_ON_ERROR)),
        ]);
        $client = new Client(['handler' => HandlerStack::create($mock)]);
        $kit = new KitService($this->kitConfig(), $client);

        $kit->subscribeLeadToConfiguredForm('lead@example.com');

        $this->assertSame(0, $mock->count());
    }
}
