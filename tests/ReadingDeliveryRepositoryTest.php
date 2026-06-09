<?php

declare(strict_types=1);

namespace App\Tests;

use App\Repository\LeadRepository;
use App\Repository\PurchaseRepository;
use App\Repository\ReadingDeliveryRepository;
use PDO;
use PHPUnit\Framework\TestCase;

final class ReadingDeliveryRepositoryTest extends TestCase
{
    public function testExistsForPurchaseAndFindByLeadId(): void
    {
        $pdo = $this->createDatabase();
        $leads = new LeadRepository($pdo);
        $purchases = new PurchaseRepository($pdo);
        $deliveries = new ReadingDeliveryRepository($pdo);

        $leadId = $leads->findOrCreateMinimalByEmail('buyer@example.com', 'Buyer');
        $purchases->upsertByReceipt(
            $leadId,
            'CB-DEL-1',
            'SALE',
            'approved',
            'USD',
            37.00,
            [['sku' => 'smr-1']],
            [],
        );

        $purchaseId = (int) $pdo->query('SELECT id FROM purchases LIMIT 1')->fetchColumn();
        self::assertFalse($deliveries->existsForPurchase($purchaseId));

        $deliveries->createPending($purchaseId, $leadId, 'readings/test.pdf');
        $deliveries->markGenerated($purchaseId);

        self::assertTrue($deliveries->existsForPurchase($purchaseId));
        $row = $deliveries->findByLeadId($leadId);
        self::assertIsArray($row);
        self::assertSame('readings/test.pdf', $row['s3_object_key'] ?? null);
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
                mirror_block_slug TEXT NULL,
                cards_json TEXT NOT NULL,
                reading_payload_json TEXT NULL,
                funnel_step TEXT NOT NULL,
                created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
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
                amount REAL NULL,
                items_json TEXT NOT NULL,
                raw_ins_json TEXT NULL,
                created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
            )'
        );
        $pdo->exec(
            'CREATE TABLE reading_deliveries (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                purchase_id INTEGER NOT NULL UNIQUE,
                lead_id INTEGER NOT NULL,
                s3_object_key TEXT NOT NULL,
                status TEXT NOT NULL DEFAULT "pending",
                generated_at TEXT NULL,
                emailed_at TEXT NULL,
                error_message TEXT NULL,
                created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
            )'
        );

        return $pdo;
    }
}
