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
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PDO;
use PHPUnit\Framework\TestCase;

final class ClickBankProductRevocationServiceTest extends TestCase
{
    /** @var list<array{request: Request}> Requests the notifier sent to the Worker webhook. */
    private array $sent = [];

    public function testCancelRebillKeepsInnerCircleAccessUntilPaidPeriodEnds(): void
    {
        $pdo = $this->createDatabase();
        $leads = new LeadRepository($pdo);
        $purchases = new PurchaseRepository($pdo);
        $service = $this->createService($purchases, new MockHandler([new Response(200)]));

        $leadId = $leads->findOrCreateMinimalByEmail('ic@example.com', 'IC Buyer');
        $purchases->upsertByReceipt($leadId, 'R1', 'SALE', 'approved', 'USD', 37.00, [['sku' => 'ic-1']], []);
        $purchases->upsertByReceipt($leadId, 'R2', 'BILL', 'approved', 'USD', 19.00, [['sku' => 'ic-1']], []);
        // The cancel event's own row is upserted as cancelled by the handler (mirrored here).
        $purchases->upsertByReceipt($leadId, 'R1', 'CANCEL-REBILL', 'cancelled', 'USD', 19.00, [['sku' => 'ic-1']], []);

        $revoked = $service->revokeForInsEvent($leadId, 'ic@example.com', [['sku' => 'ic-1']], 'cancelled', 'R1');

        // Soft cancel: one entitlement row is stamped with a paid-through date, none are hard-revoked.
        self::assertSame(1, $revoked);
        // The remaining billed row stays approved and access continues (access_until is in the future).
        self::assertSame('approved', $this->purchaseStatus($pdo, 'R2'));
        self::assertTrue($purchases->leadHasApprovedInnerCirclePurchase($leadId));

        $accessUntil = $purchases->innerCircleAccessUntil($leadId);
        self::assertNotNull($accessUntil);
        self::assertGreaterThan(date('Y-m-d H:i:s'), $accessUntil);
    }

    public function testCancelWithPastPaidPeriodRevokesAccessImmediately(): void
    {
        $pdo = $this->createDatabase();
        $leads = new LeadRepository($pdo);
        $purchases = new PurchaseRepository($pdo);
        $service = $this->createService($purchases, new MockHandler([new Response(200)]));

        $leadId = $leads->findOrCreateMinimalByEmail('old-ic@example.com', 'Old IC Buyer');
        $purchases->upsertByReceipt($leadId, 'R1', 'SALE', 'approved', 'USD', 37.00, [['sku' => 'ic-1']], []);
        // Backdate the sale so created_at + 1 month is already in the past.
        $pdo->exec("UPDATE purchases SET created_at = datetime('now', '-2 months') WHERE clickbank_receipt = 'R1'");

        $revoked = $service->revokeForInsEvent($leadId, 'old-ic@example.com', [['sku' => 'ic-1']], 'cancelled', 'R1');

        // access_until floors to now, so entitlement is effectively revoked immediately.
        self::assertSame(1, $revoked);
        self::assertFalse($purchases->leadHasApprovedInnerCirclePurchase($leadId));
    }

    public function testFirstMonthCancelFallsBackToCancelledRowAndNotifiesPaidThrough(): void
    {
        $pdo = $this->createDatabase();
        $leads = new LeadRepository($pdo);
        $purchases = new PurchaseRepository($pdo);
        $service = $this->createService($purchases, new MockHandler([new Response(200)]));

        $leadId = $leads->findOrCreateMinimalByEmail('first-month@example.com', 'First Month Buyer');
        // The buyer's ONLY Inner Circle purchase, 10 days into the first month. The cancel INS
        // upsert flips this very row BEFORE revocation runs (clickbank-ins.php persists first),
        // so no approved IC row remains when the paid-through window is computed.
        $purchases->upsertByReceipt($leadId, 'R1', 'SALE', 'approved', 'USD', 37.00, [['sku' => 'tic-1']], []);
        $pdo->exec("UPDATE purchases SET created_at = datetime('now', '-10 days') WHERE clickbank_receipt = 'R1'");
        $purchases->upsertByReceipt($leadId, 'R1', 'CANCEL-TEST-REBILL', 'cancelled', 'USD', 0.00, [['sku' => 'tic-1']], []);

        $revoked = $service->revokeForInsEvent($leadId, 'first-month@example.com', [['sku' => 'tic-1']], 'cancelled', 'R1');

        self::assertSame(1, $revoked);
        // Paid-through window = the cancelled row's created_at + 1 month (~20 days away, no floor).
        $expected = $pdo->query("SELECT datetime(created_at, '+1 month') FROM purchases WHERE clickbank_receipt = 'R1'")->fetchColumn();
        self::assertIsString($expected);
        self::assertSame($expected, $this->purchaseAccessUntil($pdo, 'R1'));
        self::assertSame($expected, $purchases->innerCircleAccessUntil($leadId));
        // The Worker is notified exactly once, with that paid-through window (soft cancel).
        self::assertCount(1, $this->sent);
        $body = self::decodeBody($this->sent[0]['request']);
        self::assertSame('cancelled', $body['kind']);
        self::assertSame('R1', $body['receipt']);
        self::assertSame((new \DateTimeImmutable($expected))->format(\DateTimeInterface::ATOM), $body['accessUntil']);
    }

    public function testCancelWithOnlyRefundedRowsNotifiesImmediateRevokeWithoutWindow(): void
    {
        $pdo = $this->createDatabase();
        $leads = new LeadRepository($pdo);
        $purchases = new PurchaseRepository($pdo);
        $service = $this->createService($purchases, new MockHandler([new Response(200)]));

        $leadId = $leads->findOrCreateMinimalByEmail('refunded@example.com', 'Refunded Buyer');
        // Only a refunded IC row exists: refunds reverse the payment, so the fallback must never
        // derive a paid-through window from it.
        $purchases->upsertByReceipt($leadId, 'R1', 'RFND', 'refunded', 'USD', 37.00, [['sku' => 'ic-1']], []);

        $revoked = $service->revokeForInsEvent($leadId, 'refunded@example.com', [['sku' => 'ic-1']], 'cancelled', 'R1');

        // Defense in depth: the cancel still notifies (never zero side effects), but with a null
        // window (immediate revoke), and the refunded row is never granted an access window.
        self::assertSame(1, $revoked);
        self::assertNull($this->purchaseAccessUntil($pdo, 'R1'));
        self::assertCount(1, $this->sent);
        $body = self::decodeBody($this->sent[0]['request']);
        self::assertSame('cancelled', $body['kind']);
        self::assertNull($body['accessUntil']);
    }

    public function testMonthTwoCancelStillAnchorsOnRemainingApprovedBillRow(): void
    {
        $pdo = $this->createDatabase();
        $leads = new LeadRepository($pdo);
        $purchases = new PurchaseRepository($pdo);
        $service = $this->createService($purchases, new MockHandler([new Response(200)]));

        $leadId = $leads->findOrCreateMinimalByEmail('month-two@example.com', 'Month Two Buyer');
        $purchases->upsertByReceipt($leadId, 'R1', 'SALE', 'approved', 'USD', 37.00, [['sku' => 'tic-1']], []);
        $purchases->upsertByReceipt($leadId, 'R1-B002', 'BILL', 'approved', 'USD', 19.00, [['sku' => 'tic-1']], []);
        $purchases->upsertByReceipt($leadId, 'R1', 'CANCEL-REBILL', 'cancelled', 'USD', 0.00, [['sku' => 'tic-1']], []);

        $revoked = $service->revokeForInsEvent($leadId, 'month-two@example.com', [['sku' => 'tic-1']], 'cancelled', 'R1');

        self::assertSame(1, $revoked);
        // The approved-row path is used (fallback untouched): the window lands on the approved
        // BILL row, the cancelled row keeps no window, and entitlement continues until period end.
        self::assertNotNull($this->purchaseAccessUntil($pdo, 'R1-B002'));
        self::assertNull($this->purchaseAccessUntil($pdo, 'R1'));
        self::assertTrue($purchases->leadHasApprovedInnerCirclePurchase($leadId));
        self::assertCount(1, $this->sent);
        $body = self::decodeBody($this->sent[0]['request']);
        self::assertSame('cancelled', $body['kind']);
        self::assertNotNull($body['accessUntil']);
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
        $stack = HandlerStack::create($mock);
        $stack->push(Middleware::history($this->sent));
        $http = new Client(['handler' => $stack]);
        $notifier = new InnerCircleRevocationNotifier($http, 'https://worker.example/revoke', 'test-secret');

        return new ClickBankProductRevocationService($purchases, $notifier);
    }

    private function purchaseStatus(PDO $pdo, string $receipt): string
    {
        $stmt = $pdo->prepare('SELECT status FROM purchases WHERE clickbank_receipt = :receipt LIMIT 1');
        $stmt->execute([':receipt' => $receipt]);

        return (string) $stmt->fetchColumn();
    }

    private function purchaseAccessUntil(PDO $pdo, string $receipt): ?string
    {
        $stmt = $pdo->prepare('SELECT access_until FROM purchases WHERE clickbank_receipt = :receipt LIMIT 1');
        $stmt->execute([':receipt' => $receipt]);
        $value = $stmt->fetchColumn();

        return is_string($value) && $value !== '' ? $value : null;
    }

    /**
     * @return array<string, mixed>
     */
    private static function decodeBody(Request $request): array
    {
        $decoded = json_decode((string) $request->getBody(), true, 512, JSON_THROW_ON_ERROR);
        self::assertIsArray($decoded);

        return $decoded;
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
