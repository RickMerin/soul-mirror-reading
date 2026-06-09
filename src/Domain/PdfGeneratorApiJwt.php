<?php

declare(strict_types=1);

namespace App\Domain;

/**
 * Builds HS256 JWT tokens for PDF Generator API (iss=api key, sub=workspace, exp=short-lived).
 */
final class PdfGeneratorApiJwt
{
    public function createToken(string $apiKey, string $apiSecret, string $workspace, int $ttlSeconds = 60): string
    {
        $header = self::base64UrlEncode(json_encode(['alg' => 'HS256', 'typ' => 'JWT'], JSON_THROW_ON_ERROR));
        $payload = self::base64UrlEncode(json_encode([
            'iss' => $apiKey,
            'sub' => $workspace,
            'exp' => time() + $ttlSeconds,
        ], JSON_THROW_ON_ERROR));
        $signature = self::base64UrlEncode(
            hash_hmac('sha256', $header . '.' . $payload, $apiSecret, true),
        );

        return $header . '.' . $payload . '.' . $signature;
    }

    private static function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
