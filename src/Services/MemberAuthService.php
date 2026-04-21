<?php

declare(strict_types=1);

namespace App\Services;

use App\Config\AppConfig;
use App\Repository\MagicLinkRepository;
use DateTimeImmutable;

final class MemberAuthService
{
    public function __construct(
        private readonly AppConfig $config,
        private readonly MagicLinkRepository $magicLinks,
    ) {}

    public function issueMagicLink(int $leadId): ?string
    {
        if ($this->config->appBaseUrl === '') {
            return null;
        }

        $rawToken = bin2hex(random_bytes(32));
        $tokenHash = hash('sha256', $rawToken);
        $expiresAt = new DateTimeImmutable('+' . max(1, $this->config->magicLinkTtlMinutes) . ' minutes');
        $this->magicLinks->createToken($leadId, $tokenHash, $expiresAt);

        return $this->config->appBaseUrl . '/member/verify.php?token=' . rawurlencode($rawToken);
    }

    public function verifyMagicToken(string $rawToken): ?int
    {
        if (!preg_match('/^[a-f0-9]{64}$/', $rawToken)) {
            return null;
        }

        return $this->magicLinks->consumeToken(hash('sha256', $rawToken), new DateTimeImmutable());
    }
}
