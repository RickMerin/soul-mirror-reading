<?php

declare(strict_types=1);

namespace App\Tests;

use App\Application\SubscriptionApiState;
use App\Application\SubscriptionReconciler;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

/**
 * Pure decision-logic tests for the reconciliation cron. The Guzzle/API and DB wiring live in
 * scripts/reconcile-clickbank-subscriptions.php and are intentionally thin/untested.
 */
final class SubscriptionReconcilerTest extends TestCase
{
    private DateTimeImmutable $now;
    private SubscriptionReconciler $reconciler;

    protected function setUp(): void
    {
        $this->now = new DateTimeImmutable('2026-07-09 12:00:00');
        $this->reconciler = new SubscriptionReconciler(3);
    }

    public function testActiveBehindExtendsToNextPaymentPlusGrace(): void
    {
        $api = new SubscriptionApiState(SubscriptionApiState::STATUS_ACTIVE, '2026-07-29', '2026-06-29');
        // DB window lapses in 2 days: a BILL INS was missed and the heartbeat is about to expire.
        $decision = $this->reconciler->decide($api, '2026-07-11 12:00:00', $this->now);

        self::assertSame(SubscriptionReconciler::ACTION_EXTEND, $decision->action);
        self::assertTrue($decision->extendOnly);
        self::assertFalse($decision->fireRevoke);
        self::assertSame('2026-08-01 00:00:00', $decision->targetAccessUntil);
    }

    public function testActiveCurrentIsNoop(): void
    {
        $api = new SubscriptionApiState(SubscriptionApiState::STATUS_ACTIVE, '2026-07-29', '2026-06-29');
        // DB window already extends beyond nextPayment + grace: nothing to do.
        $decision = $this->reconciler->decide($api, '2026-08-05 00:00:00', $this->now);

        self::assertSame(SubscriptionReconciler::ACTION_NOOP, $decision->action);
        self::assertNull($decision->targetAccessUntil);
        self::assertFalse($decision->fireRevoke);
    }

    public function testActiveWithNullWindowExtends(): void
    {
        $api = new SubscriptionApiState(SubscriptionApiState::STATUS_ACTIVE, '2026-07-29', null);
        $decision = $this->reconciler->decide($api, null, $this->now);

        self::assertSame(SubscriptionReconciler::ACTION_EXTEND, $decision->action);
        self::assertSame('2026-08-01 00:00:00', $decision->targetAccessUntil);
    }

    public function testCancelledBehindRevokesWhenPaidPeriodOver(): void
    {
        // Cancelled, paid-through already past, but the DB still grants access (window in the future).
        $api = new SubscriptionApiState(SubscriptionApiState::STATUS_CANCELED, '2026-07-04', '2026-06-04');
        $decision = $this->reconciler->decide($api, '2026-07-20 12:00:00', $this->now);

        self::assertSame(SubscriptionReconciler::ACTION_CANCEL_REVOKE, $decision->action);
        self::assertTrue($decision->fireRevoke);
        self::assertFalse($decision->extendOnly);
        self::assertSame('2026-07-04 00:00:00', $decision->targetAccessUntil);
    }

    public function testCancelledAlreadyCorrectIsNoop(): void
    {
        // Cancelled, still within paid period, DB already reflects the paid-through end.
        $api = new SubscriptionApiState(SubscriptionApiState::STATUS_CANCELED, '2026-07-29', '2026-06-29');
        $decision = $this->reconciler->decide($api, '2026-07-29 00:00:00', $this->now);

        self::assertSame(SubscriptionReconciler::ACTION_NOOP, $decision->action);
        self::assertFalse($decision->fireRevoke);
    }

    public function testCancelledUnlimitedWindowIsClampedToPaidThrough(): void
    {
        // Cancelled, still within paid period, but the DB grants indefinitely (null): clamp it down.
        $api = new SubscriptionApiState(SubscriptionApiState::STATUS_CANCELED, '2026-07-29', '2026-06-29');
        $decision = $this->reconciler->decide($api, null, $this->now);

        self::assertSame(SubscriptionReconciler::ACTION_CANCEL_PERSIST, $decision->action);
        self::assertFalse($decision->extendOnly);
        self::assertFalse($decision->fireRevoke);
        self::assertSame('2026-07-29 00:00:00', $decision->targetAccessUntil);
    }

    public function testCancelledAlreadyExpiredDoesNotRefire(): void
    {
        // Cancelled, paid-through past, DB window already in the past: no repeated webhook.
        $api = new SubscriptionApiState(SubscriptionApiState::STATUS_CANCELED, '2026-07-04', '2026-06-04');
        $decision = $this->reconciler->decide($api, '2026-07-05 00:00:00', $this->now);

        self::assertSame(SubscriptionReconciler::ACTION_NOOP, $decision->action);
        self::assertFalse($decision->fireRevoke);
    }

    public function testCancelledFallsBackToLastBillPlusMonthWhenNoNextPayment(): void
    {
        // No nextPaymentDate: paid-through = lastBill + 1 month = 2026-07-29 (in the future).
        $api = new SubscriptionApiState(SubscriptionApiState::STATUS_CANCELED, null, '2026-06-29 00:00:00');
        $decision = $this->reconciler->decide($api, null, $this->now);

        self::assertSame(SubscriptionReconciler::ACTION_CANCEL_PERSIST, $decision->action);
        self::assertSame('2026-07-29 00:00:00', $decision->targetAccessUntil);
    }

    public function testUnknownStatusIsNoop(): void
    {
        $api = new SubscriptionApiState(SubscriptionApiState::STATUS_UNKNOWN, '2026-07-29', null);
        $decision = $this->reconciler->decide($api, '2026-07-01 00:00:00', $this->now);

        self::assertSame(SubscriptionReconciler::ACTION_NOOP, $decision->action);
    }

    public function testFromOrderResponseNormalizesStatusAndDates(): void
    {
        $active = SubscriptionApiState::fromOrderResponse([
            'status' => 'ACTIVE',
            'nextPaymentDate' => '2026-08-01',
            'lastBillDate' => '2026-07-01',
        ]);
        self::assertTrue($active->isActive());
        self::assertSame('2026-08-01', $active->nextPaymentDate);
        self::assertSame('2026-07-01', $active->lastBillDate);

        $canceled = SubscriptionApiState::fromOrderResponse(['orderStatus' => 'CANCELED']);
        self::assertTrue($canceled->isCanceled());
        self::assertNull($canceled->nextPaymentDate);

        $unknown = SubscriptionApiState::fromOrderResponse(['status' => 'SOMETHING_ELSE']);
        self::assertSame(SubscriptionApiState::STATUS_UNKNOWN, $unknown->status);
    }
}
