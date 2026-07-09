<?php

declare(strict_types=1);

namespace App\Tests;

use App\Domain\InnerCircleSkus;
use App\Repository\LeadRepository;
use App\Repository\PurchaseRepository;
use PDO;
use PHPUnit\Framework\TestCase;

/**
 * Covers the rebill-heartbeat access window: an approved Inner Circle event extends access_until by
 * ~1 month + 3 days grace and never shortens a later window, while non-Inner-Circle rows are left
 * untouched. The #318 soft-cancel path is exercised by {@see ClickBankProductRevocationServiceTest}
 * and is unaffected here.
 */
final class PurchaseRepositoryAccessWindowTest extends TestCase
{
    public function testHeartbeatStampsAccessWindowAboutOneMonthAndGraceAhead(): void
    {
        $pdo = $this->createDatabase();
        $leads = new LeadRepository($pdo);
        $purchases = new PurchaseRepository($pdo);
        $leadId = $leads->findOrCreateMinimalByEmail('ic@example.com', 'IC Buyer');
        $purchases->upsertByReceipt($leadId, 'IC-1', 'SALE', 'approved', 'USD', 37.00, [['sku' => 'ic-1']], []);

        $window = $purchases->extendInnerCircleAccessWindow($leadId, InnerCircleSkus::ALL, null);

        self::assertNotNull($window);
        $stored = $this->accessUntil($pdo, 'IC-1');
        self::assertNotNull($stored);
        // 1 month + 3 days from now is between 31 and 34 days; bound generously to stay tz-safe.
        $storedTs = (int) strtotime($stored . ' UTC');
        self::assertGreaterThan(time() + 29 * 86400, $storedTs);
        self::assertLessThan(time() + 41 * 86400, $storedTs);
    }

    public function testHeartbeatNeverShortensALaterAccessUntil(): void
    {
        $pdo = $this->createDatabase();
        $leads = new LeadRepository($pdo);
        $purchases = new PurchaseRepository($pdo);
        $leadId = $leads->findOrCreateMinimalByEmail('ic@example.com', 'IC Buyer');
        $purchases->upsertByReceipt($leadId, 'IC-1', 'SALE', 'approved', 'USD', 37.00, [['sku' => 'ic-1']], []);

        // Establish a future window (now + 1 month + 3 days).
        $purchases->extendInnerCircleAccessWindow($leadId, InnerCircleSkus::ALL, null);
        $future = $this->accessUntil($pdo, 'IC-1');
        self::assertNotNull($future);

        // A heartbeat whose transaction time is far in the past would compute a past target; it must
        // NOT move the window earlier.
        $window = $purchases->extendInnerCircleAccessWindow($leadId, InnerCircleSkus::ALL, '2000-01-01 00:00:00');

        self::assertSame($future, $this->accessUntil($pdo, 'IC-1'));
        self::assertSame($future, $window);
    }

    public function testHeartbeatExtendsForwardWhenAnchorIsLater(): void
    {
        $pdo = $this->createDatabase();
        $leads = new LeadRepository($pdo);
        $purchases = new PurchaseRepository($pdo);
        $leadId = $leads->findOrCreateMinimalByEmail('ic@example.com', 'IC Buyer');
        $purchases->upsertByReceipt($leadId, 'IC-1', 'SALE', 'approved', 'USD', 37.00, [['sku' => 'ic-1']], []);

        // Seed an old, near-past window from a year-2000 anchor.
        $purchases->extendInnerCircleAccessWindow($leadId, InnerCircleSkus::ALL, '2000-01-01 00:00:00');
        $old = $this->accessUntil($pdo, 'IC-1');
        self::assertNotNull($old);

        // A fresh billing event (now) must push the window forward into the future.
        $purchases->extendInnerCircleAccessWindow($leadId, InnerCircleSkus::ALL, null);
        $new = $this->accessUntil($pdo, 'IC-1');

        self::assertNotNull($new);
        self::assertGreaterThan($old, $new);
        self::assertGreaterThan(time(), (int) strtotime($new . ' UTC'));
    }

    public function testHeartbeatTouchesOnlyInnerCircleRowsNotOtherProducts(): void
    {
        $pdo = $this->createDatabase();
        $leads = new LeadRepository($pdo);
        $purchases = new PurchaseRepository($pdo);
        $leadId = $leads->findOrCreateMinimalByEmail('mixed@example.com', 'Mixed Buyer');
        $purchases->upsertByReceipt($leadId, 'IC-1', 'BILL', 'approved', 'USD', 19.00, [['sku' => 'ic-1']], []);
        $purchases->upsertByReceipt($leadId, 'SMR-1', 'SALE', 'approved', 'USD', 47.00, [['sku' => 'smr-1']], []);

        $window = $purchases->extendInnerCircleAccessWindow($leadId, InnerCircleSkus::ALL, null);

        self::assertNotNull($window);
        self::assertNotNull($this->accessUntil($pdo, 'IC-1'));
        // The non-Inner-Circle purchase keeps its NULL window (approved, unchanged).
        self::assertNull($this->accessUntil($pdo, 'SMR-1'));
    }

    public function testHeartbeatIsNoopWhenLeadHoldsNoInnerCircleRow(): void
    {
        $pdo = $this->createDatabase();
        $leads = new LeadRepository($pdo);
        $purchases = new PurchaseRepository($pdo);
        $leadId = $leads->findOrCreateMinimalByEmail('smr@example.com', 'SMR Buyer');
        $purchases->upsertByReceipt($leadId, 'SMR-1', 'SALE', 'approved', 'USD', 47.00, [['sku' => 'smr-1']], []);

        $window = $purchases->extendInnerCircleAccessWindow($leadId, InnerCircleSkus::ALL, null);

        self::assertNull($window);
        self::assertNull($this->accessUntil($pdo, 'SMR-1'));
    }

    public function testReconcileSetterOverwriteClampsWindowDown(): void
    {
        $pdo = $this->createDatabase();
        $leads = new LeadRepository($pdo);
        $purchases = new PurchaseRepository($pdo);
        $leadId = $leads->findOrCreateMinimalByEmail('ic@example.com', 'IC Buyer');
        $purchases->upsertByReceipt($leadId, 'IC-1', 'SALE', 'approved', 'USD', 37.00, [['sku' => 'ic-1']], []);

        // Heartbeat sets a future window; a cancel reconciliation clamps it down to a paid-through end.
        $purchases->extendInnerCircleAccessWindow($leadId, InnerCircleSkus::ALL, null);
        $clamped = $purchases->setInnerCircleAccessUntilTo($leadId, InnerCircleSkus::ALL, '2020-01-01 00:00:00', false);

        self::assertSame('2020-01-01 00:00:00', $clamped);
        self::assertSame('2020-01-01 00:00:00', $this->accessUntil($pdo, 'IC-1'));
    }

    public function testReconcileSetterExtendOnlyDoesNotShorten(): void
    {
        $pdo = $this->createDatabase();
        $leads = new LeadRepository($pdo);
        $purchases = new PurchaseRepository($pdo);
        $leadId = $leads->findOrCreateMinimalByEmail('ic@example.com', 'IC Buyer');
        $purchases->upsertByReceipt($leadId, 'IC-1', 'SALE', 'approved', 'USD', 37.00, [['sku' => 'ic-1']], []);

        $future = $purchases->extendInnerCircleAccessWindow($leadId, InnerCircleSkus::ALL, null);
        self::assertNotNull($future);

        // extend-only with an earlier target must leave the later window intact.
        $result = $purchases->setInnerCircleAccessUntilTo($leadId, InnerCircleSkus::ALL, '2020-01-01 00:00:00', true);

        self::assertSame($future, $result);
        self::assertSame($future, $this->accessUntil($pdo, 'IC-1'));
    }

    private function accessUntil(PDO $pdo, string $receipt): ?string
    {
        $stmt = $pdo->prepare('SELECT access_until FROM purchases WHERE clickbank_receipt = :r LIMIT 1');
        $stmt->execute([':r' => $receipt]);
        $value = $stmt->fetchColumn();

        return is_string($value) && $value !== '' ? $value : null;
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
                access_until TEXT NULL,
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
