<?php

declare(strict_types=1);

namespace App\Tests;

use App\Application\ClickBankBuyerRemovalService;
use App\Config\AppConfig;
use App\Repository\LeadRepository;
use App\Repository\PurchaseRepository;
use App\Repository\ReadingDeliveryRepository;
use App\Services\S3ReadingStorage;
use PDO;
use PHPUnit\Framework\TestCase;

final class ClickBankBuyerRemovalServiceTest extends TestCase
{
    public function testSinglePurchaseRefundedRemovesLeadPurchasesAndDeliveries(): void
    {
        $pdo = $this->createDatabase();
        $leads = new LeadRepository($pdo);
        $purchases = new PurchaseRepository($pdo);
        $deliveries = new ReadingDeliveryRepository($pdo);
        $service = $this->createService($leads, $purchases, $deliveries);

        $leadId = $leads->findOrCreateMinimalByEmail('refunded@example.com', 'Refunded Buyer');
        $purchases->upsertByReceipt(
            $leadId,
            'CB-REF-1',
            'SALE',
            'approved',
            'USD',
            37.00,
            [['sku' => 'smr-1']],
            [],
        );
        $purchaseId = (int) $pdo->query('SELECT id FROM purchases LIMIT 1')->fetchColumn();
        $deliveries->createPending($purchaseId, $leadId, 'readings/refunded.pdf');

        $purchases->upsertByReceipt(
            $leadId,
            'CB-REF-1',
            'RFND',
            'refunded',
            'USD',
            37.00,
            [['sku' => 'smr-1']],
            [],
        );

        $service->removeBuyerIfFullyRevoked($leadId, 'refunded');

        self::assertSame(0, (int) $pdo->query('SELECT COUNT(*) FROM leads')->fetchColumn());
        self::assertSame(0, (int) $pdo->query('SELECT COUNT(*) FROM purchases')->fetchColumn());
        self::assertSame(0, (int) $pdo->query('SELECT COUNT(*) FROM reading_deliveries')->fetchColumn());
    }

    public function testPartialRefundKeepsLeadWhenAnotherPurchaseRemainsApproved(): void
    {
        $pdo = $this->createDatabase();
        $leads = new LeadRepository($pdo);
        $purchases = new PurchaseRepository($pdo);
        $deliveries = new ReadingDeliveryRepository($pdo);
        $service = $this->createService($leads, $purchases, $deliveries);

        $leadId = $leads->findOrCreateMinimalByEmail('partial@example.com', 'Partial Buyer');
        $purchases->upsertByReceipt(
            $leadId,
            'CB-PART-1',
            'SALE',
            'approved',
            'USD',
            37.00,
            [['sku' => 'smr-1']],
            [],
        );
        $purchases->upsertByReceipt(
            $leadId,
            'CB-PART-2',
            'SALE',
            'approved',
            'USD',
            27.00,
            [['sku' => 'srp-1']],
            [],
        );
        $purchases->upsertByReceipt(
            $leadId,
            'CB-PART-1',
            'RFND',
            'refunded',
            'USD',
            37.00,
            [['sku' => 'smr-1']],
            [],
        );

        $service->removeBuyerIfFullyRevoked($leadId, 'refunded');

        self::assertSame(1, (int) $pdo->query('SELECT COUNT(*) FROM leads')->fetchColumn());
        self::assertSame(2, (int) $pdo->query('SELECT COUNT(*) FROM purchases')->fetchColumn());
        self::assertTrue($purchases->buyerHasAnyPurchase($leadId));
    }

    public function testCancelRebillOnOnlyPurchaseRemovesLead(): void
    {
        $pdo = $this->createDatabase();
        $leads = new LeadRepository($pdo);
        $purchases = new PurchaseRepository($pdo);
        $deliveries = new ReadingDeliveryRepository($pdo);
        $service = $this->createService($leads, $purchases, $deliveries);

        $leadId = $leads->findOrCreateMinimalByEmail('cancelled@example.com', 'Cancelled Buyer');
        $purchases->upsertByReceipt(
            $leadId,
            'CB-IC-1',
            'SALE',
            'approved',
            'USD',
            19.00,
            [['sku' => 'ic-1']],
            [],
        );
        $purchases->upsertByReceipt(
            $leadId,
            'CB-IC-1',
            'CANCEL-REBILL',
            'cancelled',
            'USD',
            19.00,
            [['sku' => 'ic-1']],
            [],
        );

        $service->removeBuyerIfFullyRevoked($leadId, 'cancelled');

        self::assertSame(0, (int) $pdo->query('SELECT COUNT(*) FROM leads')->fetchColumn());
        self::assertSame(0, (int) $pdo->query('SELECT COUNT(*) FROM purchases')->fetchColumn());
    }

    public function testApprovedSaleDoesNotRemoveLead(): void
    {
        $pdo = $this->createDatabase();
        $leads = new LeadRepository($pdo);
        $purchases = new PurchaseRepository($pdo);
        $deliveries = new ReadingDeliveryRepository($pdo);
        $service = $this->createService($leads, $purchases, $deliveries);

        $leadId = $leads->findOrCreateMinimalByEmail('approved@example.com', 'Approved Buyer');
        $purchases->upsertByReceipt(
            $leadId,
            'CB-OK-1',
            'SALE',
            'approved',
            'USD',
            37.00,
            [['sku' => 'smr-1']],
            [],
        );

        $service->removeBuyerIfFullyRevoked($leadId, 'approved');

        self::assertSame(1, (int) $pdo->query('SELECT COUNT(*) FROM leads')->fetchColumn());
        self::assertSame(1, (int) $pdo->query('SELECT COUNT(*) FROM purchases')->fetchColumn());
    }

    public function testFindS3ObjectKeysByLeadIdReturnsAllKeys(): void
    {
        $pdo = $this->createDatabase();
        $leads = new LeadRepository($pdo);
        $purchases = new PurchaseRepository($pdo);
        $deliveries = new ReadingDeliveryRepository($pdo);

        $leadId = $leads->findOrCreateMinimalByEmail('keys@example.com', 'Keys Buyer');
        $purchases->upsertByReceipt($leadId, 'CB-K-1', 'SALE', 'approved', 'USD', 37.00, [], []);
        $purchases->upsertByReceipt($leadId, 'CB-K-2', 'SALE', 'approved', 'USD', 27.00, [], []);
        $purchaseId1 = (int) $pdo->query("SELECT id FROM purchases WHERE clickbank_receipt = 'CB-K-1'")->fetchColumn();
        $purchaseId2 = (int) $pdo->query("SELECT id FROM purchases WHERE clickbank_receipt = 'CB-K-2'")->fetchColumn();
        $deliveries->createPending($purchaseId1, $leadId, 'readings/a.pdf');
        $deliveries->createPending($purchaseId2, $leadId, 'readings/b.pdf');

        self::assertSame(
            ['readings/a.pdf', 'readings/b.pdf'],
            $deliveries->findS3ObjectKeysByLeadId($leadId),
        );
    }

    private function createService(
        LeadRepository $leads,
        PurchaseRepository $purchases,
        ReadingDeliveryRepository $deliveries,
    ): ClickBankBuyerRemovalService {
        return new ClickBankBuyerRemovalService(
            $leads,
            $purchases,
            $deliveries,
            new S3ReadingStorage($this->unconfiguredAppConfig()),
        );
    }

    private function unconfiguredAppConfig(): AppConfig
    {
        return new AppConfig(
            astroUserId: '',
            astroApiKey: '',
            tarotSource: 'local',
            sunSource: 'local',
            dataDir: '',
            sunSignTimezone: 'UTC',
            kitApiKey: '',
            kitTagName: '',
            kitTagNameBuyer: '',
            kitFormUid: '',
            kitFormEmbedScript: '',
            kitFormEmbedUid: '',
            kitFormSubscribeVia: 'none',
            pipelineFileLog: false,
            pipelineLogPath: '',
            sslCaBundlePath: '',
            dbHost: '',
            dbPort: 3306,
            dbName: '',
            dbUser: '',
            dbPass: '',
            appBaseUrl: '',
            clickbankInsSlackWebhookUrl: '',
            awsAccessKeyId: '',
            awsSecretAccessKey: '',
            awsRegion: '',
            awsS3Bucket: '',
            kitTagNameReadingDelivered: '',
            soulMirrorCardsBaseUrl: '',
            pdfGeneratorApiKey: '',
            pdfGeneratorApiSecret: '',
            pdfGeneratorApiWorkspace: '',
            pdfGeneratorApiBaseUrl: '',
            deliveryCronKey: '',
        );
    }

    private function createDatabase(): PDO
    {
        $pdo = new PDO('sqlite::memory:');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->exec('PRAGMA foreign_keys = ON');
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
                access_until TEXT NULL,
                currency TEXT NULL,
                amount REAL NULL,
                items_json TEXT NOT NULL,
                raw_ins_json TEXT NULL,
                created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (lead_id) REFERENCES leads(id) ON DELETE CASCADE
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
                updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (purchase_id) REFERENCES purchases(id) ON DELETE CASCADE,
                FOREIGN KEY (lead_id) REFERENCES leads(id) ON DELETE CASCADE
            )'
        );

        return $pdo;
    }
}
