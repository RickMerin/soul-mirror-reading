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

    public function testRefundOnInnerCircleKeepsOtherProductApproved(): void
    {
        $pdo = $this->createDatabase();
        $leads = new LeadRepository($pdo);
        $purchases = new PurchaseRepository($pdo);
        /** @var list<array{request: Request}> $history */
        $history = [];
        $service = $this->createServiceWithHistory(
            $purchases,
            new MockHandler([new Response(200)]),
            $history,
        );

        $leadId = $leads->findOrCreateMinimalByEmail('mixed@example.com', 'Mixed Buyer');
        $purchases->upsertByReceipt($leadId, 'IC-1', 'SALE', 'approved', 'USD', 37.00, [['sku' => 'ic-1']], []);
        $purchases->upsertByReceipt($leadId, 'SMR-1', 'SALE', 'approved', 'USD', 47.00, [['sku' => 'smr-1']], []);
        // Upsert alone flips the only IC row — sibling revoke count is 0, but Worker must still be notified.
        $purchases->upsertByReceipt($leadId, 'IC-1', 'RFND', 'refunded', 'USD', 37.00, [['sku' => 'ic-1']], []);

        $revoked = $service->revokeForInsEvent($leadId, 'mixed@example.com', [['sku' => 'ic-1']], 'refunded', 'IC-1');

        self::assertSame(1, $revoked);
        self::assertFalse($purchases->leadHasApprovedInnerCirclePurchase($leadId));
        self::assertTrue($purchases->leadHasApprovedPurchaseWithItemSku($leadId, 'smr-1'));

        self::assertCount(1, $history);
        $body = self::decodeNotifyBody($history[0]['request']);
        self::assertSame('refunded_or_chargeback', $body['kind']);
        self::assertNull($body['accessUntil']);
    }

    public function testRefundAfterSoftCancelRevokesAccessImmediately(): void
    {
        $pdo = $this->createDatabase();
        $leads = new LeadRepository($pdo);
        $purchases = new PurchaseRepository($pdo);
        /** @var list<array{request: Request}> $history */
        $history = [];
        $service = $this->createServiceWithHistory(
            $purchases,
            new MockHandler([new Response(200), new Response(200)]),
            $history,
        );

        $leadId = $leads->findOrCreateMinimalByEmail('tic@example.com', 'TIC Buyer');
        $purchases->upsertByReceipt($leadId, 'R1', 'SALE', 'approved', 'USD', 17.00, [['sku' => 'tic-1']], []);
        $purchases->upsertByReceipt($leadId, 'R2', 'BILL', 'approved', 'USD', 19.00, [['sku' => 'tic-1']], []);
        $purchases->upsertByReceipt($leadId, 'R1', 'CANCEL-REBILL', 'cancelled', 'USD', 19.00, [['sku' => 'tic-1']], []);

        $cancelled = $service->revokeForInsEvent($leadId, 'tic@example.com', [['sku' => 'tic-1']], 'cancelled', 'R1');
        self::assertSame(1, $cancelled);
        self::assertTrue($purchases->leadHasApprovedInnerCirclePurchase($leadId));
        $accessUntil = $purchases->innerCircleAccessUntil($leadId);
        self::assertNotNull($accessUntil);
        self::assertGreaterThan(date('Y-m-d H:i:s'), $accessUntil);

        // Refund the billed period: upsert flips R2, then hard revoke must end access + notify immediately.
        $purchases->upsertByReceipt($leadId, 'R2', 'RFND', 'refunded', 'USD', 19.00, [['sku' => 'tic-1']], []);
        $revoked = $service->revokeForInsEvent($leadId, 'tic@example.com', [['sku' => 'tic-1']], 'refunded', 'R2');

        self::assertSame(1, $revoked);
        self::assertFalse($purchases->leadHasApprovedInnerCirclePurchase($leadId));
        self::assertNull($purchases->innerCircleAccessUntil($leadId));

        self::assertCount(2, $history);
        $refundBody = self::decodeNotifyBody($history[1]['request']);
        self::assertSame('refunded_or_chargeback', $refundBody['kind']);
        self::assertNull($refundBody['accessUntil']);
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

    /**
     * @param list<array{request: Request}> $history
     */
    private function createServiceWithHistory(
        PurchaseRepository $purchases,
        MockHandler $mock,
        array &$history,
    ): ClickBankProductRevocationService {
        $stack = HandlerStack::create($mock);
        $stack->push(Middleware::history($history));
        $http = new Client(['handler' => $stack]);
        $notifier = new InnerCircleRevocationNotifier($http, 'https://worker.example/revoke', 'test-secret');

        return new ClickBankProductRevocationService($purchases, $notifier);
    }

    /**
     * @return array<string, mixed>
     */
    private static function decodeNotifyBody(Request $request): array
    {
        $decoded = json_decode((string) $request->getBody(), true, 512, JSON_THROW_ON_ERROR);
        self::assertIsArray($decoded);

        return $decoded;
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
