<?php

declare(strict_types=1);

namespace App\Application;

use DateTimeImmutable;
use Exception;

/**
 * Pure decision logic for the reconciliation cron: given the stored Inner Circle access window and
 * the ClickBank Orders API view of a subscription, decides how to correct the window and whether to
 * revoke immediately. No I/O here (the cron script performs the DB write / webhook the decision
 * asks for), so the branching is fully unit tested. See
 * scripts/reconcile-clickbank-subscriptions.php for the thin, untested API/DB wiring.
 */
final class SubscriptionReconciler
{
    /** Nothing to do. */
    public const ACTION_NOOP = 'noop';
    /** Active but our window is behind reality: extend it (heals a missed BILL INS). */
    public const ACTION_EXTEND = 'extend';
    /** Cancelled but still within the paid period: clamp the window to the paid-through end. */
    public const ACTION_CANCEL_PERSIST = 'cancel_persist';
    /** Cancelled and the paid period is over: persist the paid-through end and revoke now. */
    public const ACTION_CANCEL_REVOKE = 'cancel_revoke';

    private const TARGET_FORMAT = 'Y-m-d H:i:s';
    /** Ignore sub-minute differences so an already-correct window never churns writes/webhooks. */
    private const TOLERANCE_SECONDS = 60;

    public function __construct(private readonly int $graceDays = 3) {}

    /**
     * Decides the reconcile action for one subscription.
     *
     * @param SubscriptionApiState $api                ClickBank's view of the subscription.
     * @param string|null          $currentAccessUntil The stored access_until ('Y-m-d H:i:s'), or
     *                                                  null when no window is set (grants indefinitely).
     * @param DateTimeImmutable     $now               Reference "now" (injected for testability).
     */
    public function decide(
        SubscriptionApiState $api,
        ?string $currentAccessUntil,
        DateTimeImmutable $now,
    ): ReconcileDecision {
        if ($api->isCanceled()) {
            return $this->decideCancelled($api, $currentAccessUntil, $now);
        }
        if ($api->isActive()) {
            return $this->decideActive($api, $currentAccessUntil, $now);
        }

        return $this->noop('unknown-status');
    }

    private function decideCancelled(
        SubscriptionApiState $api,
        ?string $currentAccessUntil,
        DateTimeImmutable $now,
    ): ReconcileDecision {
        $paidThrough = $this->paidThroughEnd($api, $now);
        $target = $paidThrough->format(self::TARGET_FORMAT);
        $currentTs = $this->parse($currentAccessUntil);

        if ($paidThrough <= $now) {
            // Paid period is over. Revoke now, but only while the DB still grants access, so a
            // long-dead sub whose window is already in the past does not re-fire the webhook forever.
            $stillGrants = $currentTs === null || $currentTs > $now;
            if ($stillGrants) {
                return new ReconcileDecision(
                    self::ACTION_CANCEL_REVOKE,
                    $target,
                    false,
                    true,
                    'cancelled-paid-period-over',
                );
            }

            return $this->noop('cancelled-already-expired');
        }

        // Still within the paid period: keep access until the paid-through end, but never extend a
        // cancelled sub beyond it. Overwrite only when the DB grants MORE (a NULL/unlimited window,
        // or a later heartbeat-extended date).
        if ($currentTs === null
            || $currentTs->getTimestamp() > $paidThrough->getTimestamp() + self::TOLERANCE_SECONDS) {
            return new ReconcileDecision(
                self::ACTION_CANCEL_PERSIST,
                $target,
                false,
                false,
                'cancelled-clamp-to-paid-through',
            );
        }

        return $this->noop('cancelled-already-correct');
    }

    private function decideActive(
        SubscriptionApiState $api,
        ?string $currentAccessUntil,
        DateTimeImmutable $now,
    ): ReconcileDecision {
        $nextPayment = $this->parse($api->nextPaymentDate);
        if ($nextPayment === null) {
            // No next-payment date to anchor on: leave the window to the heartbeat.
            return $this->noop('active-no-next-payment-date');
        }

        $target = $nextPayment->modify('+' . max(0, $this->graceDays) . ' days');
        $currentTs = $this->parse($currentAccessUntil);

        if ($currentTs === null
            || $currentTs->getTimestamp() < $target->getTimestamp() - self::TOLERANCE_SECONDS) {
            return new ReconcileDecision(
                self::ACTION_EXTEND,
                $target->format(self::TARGET_FORMAT),
                true,
                false,
                'active-behind-extend-to-next-payment',
            );
        }

        return $this->noop('active-current');
    }

    /**
     * The paid-through end for a cancelled sub: nextPaymentDate when present and parseable, else
     * lastBillDate + 1 month, else now. Mirrors the #318 cancel intent ("access until the paid
     * period ends") but anchored on ClickBank's authoritative dates rather than our stored row.
     */
    private function paidThroughEnd(SubscriptionApiState $api, DateTimeImmutable $now): DateTimeImmutable
    {
        $next = $this->parse($api->nextPaymentDate);
        if ($next !== null) {
            return $next;
        }
        $lastBill = $this->parse($api->lastBillDate);
        if ($lastBill !== null) {
            return $lastBill->modify('+1 month');
        }

        return $now;
    }

    private function parse(?string $value): ?DateTimeImmutable
    {
        if ($value === null) {
            return null;
        }
        $value = trim($value);
        if ($value === '') {
            return null;
        }
        try {
            return new DateTimeImmutable($value);
        } catch (Exception) {
            return null;
        }
    }

    private function noop(string $reason): ReconcileDecision
    {
        return new ReconcileDecision(self::ACTION_NOOP, null, false, false, $reason);
    }
}
