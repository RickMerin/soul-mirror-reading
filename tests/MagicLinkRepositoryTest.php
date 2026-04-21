<?php

declare(strict_types=1);

namespace App\Tests;

use App\Repository\MagicLinkRepository;
use DateTimeImmutable;
use PDO;
use PHPUnit\Framework\TestCase;

final class MagicLinkRepositoryTest extends TestCase
{
    public function testConsumeTokenMarksTokenUsedAndReturnsLeadId(): void
    {
        $pdo = new PDO('sqlite::memory:');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->exec(
            'CREATE TABLE member_magic_links (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                lead_id INTEGER NOT NULL,
                token_hash TEXT NOT NULL,
                expires_at TEXT NOT NULL,
                used_at TEXT NULL,
                created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
            )'
        );

        $repo = new MagicLinkRepository($pdo);
        $tokenHash = hash('sha256', str_repeat('a', 64));
        $expiresAt = new DateTimeImmutable('+10 minutes');
        $repo->createToken(12, $tokenHash, $expiresAt);

        $leadId = $repo->consumeToken($tokenHash, new DateTimeImmutable());
        self::assertSame(12, $leadId);
        self::assertNull($repo->consumeToken($tokenHash, new DateTimeImmutable('+1 minute')));
    }
}
