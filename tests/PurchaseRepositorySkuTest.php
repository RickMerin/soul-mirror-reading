<?php

declare(strict_types=1);

namespace App\Tests;

use App\Repository\LeadRepository;
use App\Repository\PurchaseRepository;
use PDO;
use PHPUnit\Framework\TestCase;

final class PurchaseRepositorySkuTest extends TestCase
{
    public function testLeadHasApprovedPurchaseWithItemSkuIsFalseWhenSkuEmpty(): void
    {
        $pdo = $this->createDatabase();
        $repo = new PurchaseRepository($pdo);

        self::assertFalse($repo->leadHasApprovedPurchaseWithItemSku(1, ''));
    }

    public function testLeadHasApprovedPurchaseWithItemSkuMatchesLineItemSku(): void
    {
        $pdo = $this->createDatabase();
        $leads = new LeadRepository($pdo);
        $purchases = new PurchaseRepository($pdo);
        $leadId = $leads->findOrCreateMinimalByEmail('buyer@example.com', 'Buyer');

        $purchases->upsertByReceipt(
            $leadId,
            'CB-SMR1',
            'SALE',
            'approved',
            'USD',
            47.00,
            [['sku' => 'smr-1', 'item' => 'smr-1']],
            []
        );

        self::assertTrue($purchases->leadHasApprovedPurchaseWithItemSku($leadId, 'smr-1'));
        self::assertFalse($purchases->leadHasApprovedPurchaseWithItemSku($leadId, 'smr-2'));
    }

    public function testLeadHasApprovedPurchaseWithItemSkuIgnoresNonApprovedStatus(): void
    {
        $pdo = $this->createDatabase();
        $leads = new LeadRepository($pdo);
        $purchases = new PurchaseRepository($pdo);
        $leadId = $leads->findOrCreateMinimalByEmail('buyer@example.com', 'Buyer');

        $purchases->upsertByReceipt(
            $leadId,
            'CB-RFND',
            'RFND',
            'refunded',
            'USD',
            97.00,
            [['sku' => 'smr-2']],
            []
        );

        self::assertFalse($purchases->leadHasApprovedPurchaseWithItemSku($leadId, 'smr-2'));
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
