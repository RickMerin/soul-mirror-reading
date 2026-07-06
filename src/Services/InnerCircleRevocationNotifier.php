<?php

declare(strict_types=1);

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Throwable;

/**
 * Notifies the Inner Circle Cloudflare Worker when a buyer loses ic-1 / ic-1-ds access.
 */
final class InnerCircleRevocationNotifier
{
    public function __construct(
        private readonly ClientInterface $http,
        private readonly string $revokeWebhookUrl,
        private readonly string $hmacSecret,
    ) {}

    public function notifyRevoked(string $email, string $reason, ?string $receipt): void
    {
        if ($this->revokeWebhookUrl === '' || $this->hmacSecret === '') {
            return;
        }

        $email = strtolower(trim($email));
        if ($email === '' || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            return;
        }

        $body = [
            'email' => $email,
            'reason' => $reason,
            'receipt' => $receipt ?? '',
        ];
        $json = json_encode($body, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
        $signature = hash_hmac('sha256', $json, $this->hmacSecret);

        try {
            $this->http->request('POST', $this->revokeWebhookUrl, [
                'timeout' => 10.0,
                'http_errors' => false,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'X-IC-Signature' => $signature,
                ],
                'body' => $json,
            ]);
        } catch (Throwable $e) {
            error_log('Inner Circle revoke webhook failed: ' . $e->getMessage());
        }
    }

    public static function fromEnvironment(ClientInterface $http): self
    {
        $url = self::env('IC_REVOKE_WEBHOOK_URL');
        $secret = self::env('IC_HMAC_SECRET');

        return new self($http, $url, $secret);
    }

    private static function env(string $key): string
    {
        $v = $_ENV[$key] ?? getenv($key);

        return is_string($v) && $v !== '' ? $v : '';
    }
}
