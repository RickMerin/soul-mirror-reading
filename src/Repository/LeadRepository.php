<?php

declare(strict_types=1);

namespace App\Repository;

use PDO;
use RuntimeException;

final class LeadRepository
{
    public function __construct(private readonly PDO $pdo) {}

    /**
     * @param array<string,mixed> $cards
     * @param array<string,mixed>|null $readingPayload
     */
    public function upsertLead(
        string $email,
        string $name,
        string $dob,
        string $gender,
        array $cards,
        ?array $readingPayload = null,
        ?string $mirrorBlockSlug = null,
    ): string {
        $uuid = $this->lookupUuidByEmail($email) ?? $this->uuidV4();

        $stmt = $this->pdo->prepare(
            'INSERT INTO leads (uuid, email, name, dob, gender, mirror_block_slug, cards_json, reading_payload_json, funnel_step)
             VALUES (:uuid, :email, :name, :dob, :gender, :mirror_block_slug, :cards_json, :reading_payload_json, :funnel_step)
             ON DUPLICATE KEY UPDATE
                name = VALUES(name),
                dob = VALUES(dob),
                gender = VALUES(gender),
                mirror_block_slug = COALESCE(VALUES(mirror_block_slug), mirror_block_slug),
                cards_json = VALUES(cards_json),
                reading_payload_json = VALUES(reading_payload_json),
                funnel_step = VALUES(funnel_step),
                updated_at = CURRENT_TIMESTAMP'
        );
        $stmt->execute([
            ':uuid' => $uuid,
            ':email' => $email,
            ':name' => $name,
            ':dob' => $dob,
            ':gender' => $gender,
            ':mirror_block_slug' => $mirrorBlockSlug,
            ':cards_json' => json_encode($cards, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR),
            ':reading_payload_json' => $readingPayload === null
                ? null
                : json_encode($readingPayload, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR),
            ':funnel_step' => 'unlock-reading',
        ]);

        return $uuid;
    }

    public function findIdByEmail(string $email): ?int
    {
        $stmt = $this->pdo->prepare('SELECT id FROM leads WHERE email = :email LIMIT 1');
        $stmt->execute([':email' => $email]);
        $id = $stmt->fetchColumn();

        return is_numeric($id) ? (int) $id : null;
    }

    public function findOrCreateMinimalByEmail(string $email, string $name = 'ClickBank Buyer'): int
    {
        $existingId = $this->findIdByEmail($email);
        if ($existingId !== null) {
            return $existingId;
        }

        $uuid = $this->uuidV4();
        $stmt = $this->pdo->prepare(
            'INSERT INTO leads (uuid, email, name, dob, gender, cards_json, reading_payload_json, funnel_step)
             VALUES (:uuid, :email, :name, :dob, :gender, :cards_json, :reading_payload_json, :funnel_step)'
        );
        $stmt->execute([
            ':uuid' => $uuid,
            ':email' => $email,
            ':name' => $name !== '' ? $name : 'ClickBank Buyer',
            ':dob' => '1900-01-01',
            ':gender' => 'unknown',
            ':cards_json' => json_encode(new \stdClass(), JSON_THROW_ON_ERROR),
            ':reading_payload_json' => null,
            ':funnel_step' => 'unlock-reading',
        ]);

        $newId = $this->findIdByEmail($email);
        if ($newId === null) {
            throw new RuntimeException('Unable to create lead for ClickBank INS payload.');
        }

        return $newId;
    }

    public function updateMirrorBlockSlug(int $leadId, string $slug): void
    {
        $stmt = $this->pdo->prepare(
            'UPDATE leads SET mirror_block_slug = :slug, updated_at = CURRENT_TIMESTAMP WHERE id = :id'
        );
        $stmt->execute([
            ':slug' => strtolower(trim($slug)),
            ':id' => $leadId,
        ]);
    }

    /**
     * @return array<string,mixed>|null
     */
    public function findById(int $leadId): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM leads WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $leadId]);
        $row = $stmt->fetch();

        return is_array($row) ? $row : null;
    }

    private function lookupUuidByEmail(string $email): ?string
    {
        $stmt = $this->pdo->prepare('SELECT uuid FROM leads WHERE email = :email LIMIT 1');
        $stmt->execute([':email' => $email]);
        $value = $stmt->fetchColumn();

        return is_string($value) && $value !== '' ? $value : null;
    }

    private function uuidV4(): string
    {
        $bytes = random_bytes(16);
        $bytes[6] = chr(ord($bytes[6]) & 0x0f | 0x40);
        $bytes[8] = chr(ord($bytes[8]) & 0x3f | 0x80);
        $hex = bin2hex($bytes);

        return sprintf(
            '%s-%s-%s-%s-%s',
            substr($hex, 0, 8),
            substr($hex, 8, 4),
            substr($hex, 12, 4),
            substr($hex, 16, 4),
            substr($hex, 20, 12),
        );
    }
}
