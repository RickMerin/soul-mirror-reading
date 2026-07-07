<?php

declare(strict_types=1);

namespace App\Tests;

use App\Services\InnerCircleRevocationNotifier;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class InnerCircleRevocationNotifierTest extends TestCase
{
    /** @var list<array{request: Request}> */
    private array $sent = [];

    public function testCancelPayloadCarriesKindAndAccessUntil(): void
    {
        $notifier = $this->makeNotifier();

        $notifier->notifyRevoked('Buyer@Example.com', 'cancelled', 'R1', '2026-08-05T03:00:00+00:00');

        self::assertCount(1, $this->sent);
        $request = $this->sent[0]['request'];
        $body = self::decodeBody($request);
        self::assertSame('buyer@example.com', $body['email']);
        self::assertSame('cancelled', $body['reason']);
        self::assertSame('cancelled', $body['kind']);
        self::assertSame('R1', $body['receipt']);
        self::assertSame('2026-08-05T03:00:00+00:00', $body['accessUntil']);

        $signature = $request->getHeaderLine('X-IC-Signature');
        $expected = hash_hmac('sha256', (string) $request->getBody(), 'test-secret');
        self::assertSame($expected, $signature);
    }

    public function testRefundPayloadHasImmediateNullAccessUntil(): void
    {
        $notifier = $this->makeNotifier();

        $notifier->notifyRevoked('buyer@example.com', 'refunded', 'R2', null);

        $body = self::decodeBody($this->sent[0]['request']);
        self::assertSame('refunded_or_chargeback', $body['kind']);
        self::assertNull($body['accessUntil']);
    }

    public function testMissingWebhookUrlDoesNotSendRequest(): void
    {
        $mock = new MockHandler([]);
        $http = new Client(['handler' => HandlerStack::create($mock)]);
        $notifier = new InnerCircleRevocationNotifier($http, '', 'test-secret');

        // No webhook URL: no request is attempted (an empty MockHandler would throw if one were).
        $notifier->notifyRevoked('buyer@example.com', 'cancelled', 'R1', '2026-08-05T03:00:00+00:00');

        $this->addToAssertionCount(1);
    }

    private function makeNotifier(): InnerCircleRevocationNotifier
    {
        $mock = new MockHandler([new Response(200), new Response(200)]);
        $stack = HandlerStack::create($mock);
        $stack->push(Middleware::history($this->sent));
        $http = new Client(['handler' => $stack]);

        return new InnerCircleRevocationNotifier($http, 'https://worker.example/revoke', 'test-secret');
    }

    /**
     * @return array<string, mixed>
     */
    private static function decodeBody(Request $request): array
    {
        $decoded = json_decode((string) $request->getBody(), true, 512, JSON_THROW_ON_ERROR);
        self::assertIsArray($decoded);

        return $decoded;
    }
}
