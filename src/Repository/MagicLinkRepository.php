<?php

declare(strict_types=1);

namespace App\Repository;

use DateTimeImmutable;
use PDO;

final class MagicLinkRepository
{
    public function __construct(private readonly PDO $pdo) {}

    public function createToken(int $leadId, string $tokenHash, DateTimeImmutable $expiresAt): void
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO member_magic_links (lead_id, token_hash, expires_at)
             VALUES (:lead_id, :token_hash, :expires_at)'
        );
        $stmt->execute([
            ':lead_id' => $leadId,
            ':token_hash' => $tokenHash,
            ':expires_at' => $expiresAt->format('Y-m-d H:i:s'),
        ]);
    }

    public function consumeToken(string $tokenHash, DateTimeImmutable $now): ?int
    {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare(
                'SELECT id, lead_id FROM member_magic_links
                 WHERE token_hash = :token_hash
                   AND used_at IS NULL
                   AND expires_at >= :now
                 LIMIT 1'
            );
            $stmt->execute([
                ':token_hash' => $tokenHash,
                ':now' => $now->format('Y-m-d H:i:s'),
            ]);
            $row = $stmt->fetch();
            if (!is_array($row) || !isset($row['id'], $row['lead_id'])) {
                $this->pdo->rollBack();

                return null;
            }

            $update = $this->pdo->prepare(
                'UPDATE member_magic_links SET used_at = :used_at WHERE id = :id'
            );
            $update->execute([
                ':used_at' => $now->format('Y-m-d H:i:s'),
                ':id' => (int) $row['id'],
            ]);
            $this->pdo->commit();

            return (int) $row['lead_id'];
        } catch (\Throwable) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            return null;
        }
    }
}
