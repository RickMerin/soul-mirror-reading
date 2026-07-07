<?php

declare(strict_types=1);

namespace App\Tests;

use App\Application\ClickBankProductRevocationService;
use App\Repository\LeadRepository;
use App\Repository\PurchaseRepository;
use App\Services\InnerCircleRevocationNotifier;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PDO;
use PHPUnit\Framework\TestCase;

final class ClickBankProductRevocationServiceTest extends TestCase
{
    public function testCancelRebillRevokesAllInnerCircleReceiptsIncludingRebills(): void
    {
        $pdo = $this->createDatabase();
        $leads = new LeadRepository($pdo);
        $purchases = new PurchaseRepository($pdo);
        $service = $this->createService($purchases, new MockHandler([new Response(200)]));

        $leadId = $leads->findOrCreateMinimalByEmail('ic@example.com', 'IC Buyer');
        $purchases->upsertByReceipt($leadId, 'R1', 'SALE', 'approved', 'USD', 37.00, [['sku' => 'ic-1']], []);
        $purchases->upsertByReceipt($leadId, 'R2', 'BILL', 'approved', 'USD', 19.00, [['sku' => 'ic-1']], []);
        $purchases->upsertByReceipt($leadId, 'R1', 'CANCEL-REBILL', 'cancelled', 'USD', 19.00, [['sku' => 'ic-1']], []);

        $revoked = $service->revokeForInsEvent($leadId, 'ic@example.com', [['sku' => 'ic-1']], 'cancelled', 'R1');

        self::assertSame(1, $revoked);
        self::assertFalse($purchases->leadHasApprovedInnerCirclePurchase($leadId));
        self::assertSame('cancelled', $this->purchaseStatus($pdo, 'R2'));
    }

    public function testRefundOnInnerCircleKeepsOtherProductApproved(): void
    {
        $pdo = $this->createDatabase();
        $leads = new LeadRepository($pdo);
        $purchases = new PurchaseRepository($pdo);
        $service = $this->createService($purchases, new MockHandler([new Response(200)]));

        $leadId = $leads->findOrCreateMinimalByEmail('mixed@example.com', 'Mixed Buyer');
        $purchases->upsertByReceipt($leadId, 'IC-1', 'SALE', 'approved', 'USD', 37.00, [['sku' => 'ic-1']], []);
        $purchases->upsertByReceipt($leadId, 'SMR-1', 'SALE', 'approved', 'USD', 47.00, [['sku' => 'smr-1']], []);
        $purchases->upsertByReceipt($leadId, 'IC-1', 'RFND', 'refunded', 'USD', 37.00, [['sku' => 'ic-1']], []);

        $revoked = $service->revokeForInsEvent($leadId, 'mixed@example.com', [['sku' => 'ic-1']], 'refunded', 'IC-1');

        self::assertSame(0, $revoked);
        self::assertFalse($purchases->leadHasApprovedInnerCirclePurchase($leadId));
        self::assertTrue($purchases->leadHasApprovedPurchaseWithItemSku($leadId, 'smr-1'));
    }

    public function testApprovedSaleDoesNotRevoke(): void
    {
        $pdo = $this->createDatabase();
        $leads = new LeadRepository($pdo);
        $purchases = new PurchaseRepository($pdo);
        $service = $this->createService($purchases, new MockHandler([]));

        $leadId = $leads->findOrCreateMinimalByEmail('ok@example.com', 'OK Buyer');
        $purchases->upsertByReceipt($leadId, 'R1', 'SALE', 'approved', 'USD', 37.00, [['sku' => 'ic-1']], []);

        $revoked = $service->revokeForInsEvent($leadId, 'ok@example.com', [['sku' => 'ic-1']], 'approved', 'R1');

        self::assertSame(0, $revoked);
        self::assertTrue($purchases->leadHasApprovedInnerCirclePurchase($leadId));
    }

    private function createService(PurchaseRepository $purchases, MockHandler $mock): ClickBankProductRevocationService
    {
        $http = new Client(['handler' => HandlerStack::create($mock)]);
        $notifier = new InnerCircleRevocationNotifier($http, 'https://worker.example/revoke', 'test-secret');

        return new ClickBankProductRevocationService($purchases, $notifier);
    }

    private function purchaseStatus(PDO $pdo, string $receipt): string
    {
        $stmt = $pdo->prepare('SELECT status FROM purchases WHERE clickbank_receipt = :receipt LIMIT 1');
        $stmt->execute([':receipt' => $receipt]);

        return (string) $stmt->fetchColumn();
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
