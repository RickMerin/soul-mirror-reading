<?php

declare(strict_types=1);

namespace App\Application;

/**
 * The outcome of {@see SubscriptionReconciler::decide()} for one subscription: what the reconcile
 * cron should do to the stored Inner Circle access window, and whether it should fire the revoke
 * webhook. This is a plain value object; the cron script performs the DB write / webhook.
 */
final class ReconcileDecision
{
    public function __construct(
        /** One of the SubscriptionReconciler::ACTION_* constants. */
        public readonly string $action,
        /** Target access_until as 'Y-m-d H:i:s', or null for a no-op. */
        public readonly ?string $targetAccessUntil,
        /** True to extend-only (never shorten); false to overwrite unconditionally. */
        public readonly bool $extendOnly,
        /** True to notify the revoke webhook (immediate KV revoke). */
        public readonly bool $fireRevoke,
        /** Short machine-readable reason for the STDOUT/log summary. */
        public readonly string $reason,
    ) {}

    public function isNoop(): bool
    {
        return $this->action === SubscriptionReconciler::ACTION_NOOP;
    }
}
