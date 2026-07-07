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

    /**
     * Notifies the worker that a buyer lost (or is scheduled to lose) Inner Circle access.
     *
     * @param string      $reason      Normalized purchases.status: cancelled / refunded / chargeback.
     * @param string|null $accessUntil ISO 8601 timestamp when access should end (soft cancel), or
     *                                 null to revoke immediately (refund / chargeback).
     */
    public function notifyRevoked(string $email, string $reason, ?string $receipt, ?string $accessUntil = null): void
    {
        if ($this->revokeWebhookUrl === '') {
            error_log('Inner Circle revoke webhook skipped: IC_REVOKE_WEBHOOK_URL is not set (revocation not propagated to the worker).');

            return;
        }
        if ($this->hmacSecret === '') {
            error_log('Inner Circle revoke webhook skipped: IC_HMAC_SECRET is not set (cannot sign the request).');

            return;
        }

        $email = strtolower(trim($email));
        if ($email === '' || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            return;
        }

        $kind = strtolower(trim($reason)) === 'cancelled' ? 'cancelled' : 'refunded_or_chargeback';

        $body = [
            'email' => $email,
            'reason' => $reason,
            'receipt' => $receipt ?? '',
            'kind' => $kind,
            'accessUntil' => self::toIso8601($accessUntil),
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

    /**
     * Normalizes a DB timestamp (typically "Y-m-d H:i:s") to an ISO 8601 string, or null.
     * A revoke-at value in the past is treated as null (revoke immediately).
     */
    private static function toIso8601(?string $timestamp): ?string
    {
        if ($timestamp === null) {
            return null;
        }
        $timestamp = trim($timestamp);
        if ($timestamp === '') {
            return null;
        }

        try {
            $dt = new \DateTimeImmutable($timestamp);
        } catch (\Exception) {
            return null;
        }

        return $dt->format(\DateTimeInterface::ATOM);
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
