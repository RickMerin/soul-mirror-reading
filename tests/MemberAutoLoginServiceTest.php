<?php

declare(strict_types=1);

namespace App\Tests;

use App\Repository\LeadRepository;
use App\Repository\PurchaseRepository;
use App\Services\MemberAutoLoginService;
use PDO;
use PHPUnit\Framework\TestCase;

final class MemberAutoLoginServiceTest extends TestCase
{
    public function testResolveAuthorizedLeadIdReturnsLeadIdForPurchasedBuyerEmail(): void
    {
        $pdo = $this->createDatabase();
        $this->insertLead($pdo, 10, 'buyer@example.com');
        $this->insertPurchase($pdo, 10, 'approved');

        $service = new MemberAutoLoginService(
            new LeadRepository($pdo),
            new PurchaseRepository($pdo),
        );

        self::assertSame(10, $service->resolveAuthorizedLeadId('buyer@example.com'));
    }

    public function testResolveAuthorizedLeadIdReturnsNullForLeadWithoutPurchase(): void
    {
        $pdo = $this->createDatabase();
        $this->insertLead($pdo, 22, 'nopurchase@example.com');

        $service = new MemberAutoLoginService(
            new LeadRepository($pdo),
            new PurchaseRepository($pdo),
        );

        self::assertNull($service->resolveAuthorizedLeadId('nopurchase@example.com'));
    }

    public function testResolveAuthorizedLeadIdReturnsNullForInvalidEmail(): void
    {
        $pdo = $this->createDatabase();
        $service = new MemberAutoLoginService(
            new LeadRepository($pdo),
            new PurchaseRepository($pdo),
        );

        self::assertNull($service->resolveAuthorizedLeadId('not-an-email'));
    }

    public function testResolveAuthorizedLeadIdTreatsKnownCompleteStatusAsAuthorized(): void
    {
        $pdo = $this->createDatabase();
        $this->insertLead($pdo, 33, 'complete@example.com');
        $this->insertPurchase($pdo, 33, 'completed');

        $service = new MemberAutoLoginService(
            new LeadRepository($pdo),
            new PurchaseRepository($pdo),
        );

        self::assertSame(33, $service->resolveAuthorizedLeadId('complete@example.com'));
    }

    private function createDatabase(): PDO
    {
        $pdo = new PDO('sqlite::memory:');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->exec(
            'CREATE TABLE leads (
                id INTEGER PRIMARY KEY,
                uuid TEXT NOT NULL,
                email TEXT NOT NULL,
                name TEXT NULL,
                dob TEXT NULL,
                gender TEXT NULL,
                cards_json TEXT NULL,
                reading_payload_json TEXT NULL,
                funnel_step TEXT NULL,
                created_at TEXT NULL,
                updated_at TEXT NULL
            )'
        );
        $pdo->exec(
            'CREATE TABLE purchases (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                lead_id INTEGER NOT NULL,
                clickbank_receipt TEXT NULL,
                txn_type TEXT NULL,
                status TEXT NOT NULL,
                currency TEXT NULL,
                amount TEXT NULL,
                items_json TEXT NOT NULL,
                raw_ins_json TEXT NULL,
                created_at TEXT NULL,
                updated_at TEXT NULL
            )'
        );

        return $pdo;
    }

    private function insertLead(PDO $pdo, int $id, string $email): void
    {
        $stmt = $pdo->prepare(
            'INSERT INTO leads (id, uuid, email, name, dob, gender, cards_json, reading_payload_json, funnel_step)
             VALUES (:id, :uuid, :email, :name, :dob, :gender, :cards_json, :reading_payload_json, :funnel_step)'
        );
        $stmt->execute([
            ':id' => $id,
            ':uuid' => sprintf('00000000-0000-4000-8000-%012d', $id),
            ':email' => $email,
            ':name' => 'Test Buyer',
            ':dob' => '1990-01-01',
            ':gender' => 'female',
            ':cards_json' => '{}',
            ':reading_payload_json' => null,
            ':funnel_step' => 'unlock-reading',
        ]);
    }

    private function insertPurchase(PDO $pdo, int $leadId, string $status): void
    {
        $stmt = $pdo->prepare(
            'INSERT INTO purchases (lead_id, clickbank_receipt, txn_type, status, items_json)
             VALUES (:lead_id, :receipt, :txn_type, :status, :items_json)'
        );
        $stmt->execute([
            ':lead_id' => $leadId,
            ':receipt' => 'R-' . $leadId . '-' . $status,
            ':txn_type' => 'SALE',
            ':status' => $status,
            ':items_json' => '[]',
        ]);
    }
}
