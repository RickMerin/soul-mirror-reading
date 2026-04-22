<?php

declare(strict_types=1);

namespace App\Tests;

use App\Repository\LeadRepository;
use App\Repository\PurchaseRepository;
use PDO;
use PHPUnit\Framework\TestCase;

final class ClickbankPersistenceRepositoryTest extends TestCase
{
    public function testFindOrCreateMinimalByEmailCreatesLeadAndReturnsStableId(): void
    {
        $pdo = $this->createDatabase();
        $leads = new LeadRepository($pdo);

        $firstId = $leads->findOrCreateMinimalByEmail('buyer@example.com', 'Buyer Name');
        $secondId = $leads->findOrCreateMinimalByEmail('buyer@example.com', 'Another Name');

        self::assertSame($firstId, $secondId);
        $row = $pdo->query("SELECT email, name, funnel_step FROM leads WHERE id = {$firstId}")->fetch();
        self::assertIsArray($row);
        self::assertSame('buyer@example.com', $row['email']);
        self::assertSame('Buyer Name', $row['name']);
        self::assertSame('unlock-reading', $row['funnel_step']);
    }

    public function testUpsertByReceiptIsIdempotentAndUpdatesRow(): void
    {
        $pdo = $this->createDatabase();
        $leads = new LeadRepository($pdo);
        $purchases = new PurchaseRepository($pdo);
        $leadId = $leads->findOrCreateMinimalByEmail('buyer@example.com', 'Buyer Name');

        $purchases->upsertByReceipt(
            $leadId,
            'CB-123',
            'SALE',
            'approved',
            'USD',
            37.00,
            [['itemNo' => '1']],
            ['receipt' => 'CB-123', 'transactionType' => 'SALE']
        );
        $purchases->upsertByReceipt(
            $leadId,
            'CB-123',
            'RFND',
            'refunded',
            'USD',
            37.00,
            [['itemNo' => '1']],
            ['receipt' => 'CB-123', 'transactionType' => 'RFND']
        );

        $count = (int) $pdo->query("SELECT COUNT(*) FROM purchases WHERE clickbank_receipt = 'CB-123'")->fetchColumn();
        self::assertSame(1, $count);
        $row = $pdo->query("SELECT txn_type, status, raw_ins_json FROM purchases WHERE clickbank_receipt = 'CB-123'")->fetch();
        self::assertIsArray($row);
        self::assertSame('RFND', $row['txn_type']);
        self::assertSame('refunded', $row['status']);
        self::assertStringContainsString('"transactionType":"RFND"', (string) $row['raw_ins_json']);
    }

    private function createDatabase(): PDO
    {
        $pdo = new PDO('sqlite::memory:');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->exec(
            'CREATE TABLE leads (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                uuid TEXT NOT NULL,
                email TEXT NOT NULL UNIQUE,
                name TEXT NOT NULL,
                dob TEXT NOT NULL,
                gender TEXT NOT NULL,
                cards_json TEXT NOT NULL,
                reading_payload_json TEXT NULL,
                funnel_step TEXT NOT NULL,
                created_at TEXT DEFAULT CURRENT_TIMESTAMP,
                updated_at TEXT DEFAULT CURRENT_TIMESTAMP
            )'
        );
        $pdo->exec(
            'CREATE TABLE purchases (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                lead_id INTEGER NOT NULL,
                clickbank_receipt TEXT NULL UNIQUE,
                txn_type TEXT NULL,
                status TEXT NOT NULL DEFAULT "pending",
                currency TEXT NULL,
                amount NUMERIC NULL,
                items_json TEXT NOT NULL,
                raw_ins_json TEXT NULL,
                created_at TEXT DEFAULT CURRENT_TIMESTAMP,
                updated_at TEXT DEFAULT CURRENT_TIMESTAMP
            )'
        );

        return $pdo;
    }
}
