#!/usr/bin/env php
<?php

declare(strict_types=1);

/**
 * Reconciles Inner Circle subscriptions against the ClickBank Orders API v1.3.
 *
 * Why: ClickBank does NOT emit an INS for cancels of TEST subscriptions, and for real subs INS
 * delivery is retry-limited (a few retries then gone forever). A single missed CANCEL-REBILL INS
 * would otherwise leave a member with access while paying nothing; a missed BILL INS would lock a
 * paying member out once the rebill heartbeat window lapses. This cron closes both gaps by asking
 * ClickBank directly, and is safe to run repeatedly (idempotent).
 *
 * For each distinct receipt in the active-member set (an approved purchase containing an Inner
 * Circle SKU) it GETs orders2/{receipt}, then, via {@see App\Application\SubscriptionReconciler}:
 *   - ACTIVE + our window behind nextPaymentDate  -> extend access_until to nextPaymentDate + grace.
 *   - CANCELED + still in paid period             -> clamp access_until to the paid-through end.
 *   - CANCELED + paid period over (DB still grants)-> persist paid-through end + revoke via the
 *     same InnerCircleRevocationNotifier the INS path uses (identical Worker webhook + HMAC).
 *
 * Usage (from project root):
 *   php scripts/reconcile-clickbank-subscriptions.php [--limit=500] [--sleep-ms=250] [--dry-run]
 *
 * Requires in .env: DB_*, CLICKBANK_DEV_KEY, CLICKBANK_API_KEY, and (to propagate revokes)
 * IC_REVOKE_WEBHOOK_URL + IC_HMAC_SECRET.
 *
 * cPanel cron (every 6 hours; adjust the path to your deploy layout, mirroring deliver-readings.php):
 *   17 0,6,12,18 * * * php /home/USER/path/to/scripts/reconcile-clickbank-subscriptions.php --limit=500 >> /home/USER/path/to/storage/logs/reconcile-cron.log 2>&1
 *
 * Exit codes: 0 on success (even when it corrects rows); 1 on missing config; 2 on a ClickBank auth
 * failure (bad dev/API key). Per-receipt HTTP errors (404/5xx) are tolerated and counted, not fatal.
 */

use App\Application\SubscriptionApiState;
use App\Application\SubscriptionReconciler;
use App\Config\AppConfig;
use App\Domain\InnerCircleSkus;
use App\Infrastructure\DatabaseConnection;
use App\Logging\ClickBankInsLogger;
use App\Repository\PurchaseRepository;
use App\Services\InnerCircleRevocationNotifier;
use GuzzleHttp\Client;

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit(1);
}

$projectRoot = dirname(__DIR__);
require $projectRoot . '/vendor/autoload.php';

$options = getopt('', ['limit::', 'sleep-ms::', 'dry-run']);
$limit = isset($options['limit']) && is_numeric($options['limit']) ? max(1, (int) $options['limit']) : 500;
$sleepMs = isset($options['sleep-ms']) && is_numeric($options['sleep-ms']) ? max(0, (int) $options['sleep-ms']) : 250;
$dryRun = array_key_exists('dry-run', $options);

$config = AppConfig::load($projectRoot);
if (!$config->hasDatabaseConfig()) {
    fwrite(STDERR, 'Database not configured: set DB_NAME and DB_USER in .env.' . PHP_EOL);
    exit(1);
}

$devKey = reconcileEnv('CLICKBANK_DEV_KEY');
$apiKey = reconcileEnv('CLICKBANK_API_KEY');
if ($devKey === '' || $apiKey === '') {
    fwrite(STDERR, 'ClickBank API not configured: set CLICKBANK_DEV_KEY and CLICKBANK_API_KEY in .env.' . PHP_EOL);
    exit(1);
}

$logger = new ClickBankInsLogger($projectRoot);
$reconciler = new SubscriptionReconciler();

try {
    $pdo = DatabaseConnection::fromConfig($config);
    $purchases = new PurchaseRepository($pdo);
    $http = new Client($config->guzzleClientConfig());
    $notifier = InnerCircleRevocationNotifier::fromEnvironment($http);
    $receipts = $purchases->findActiveInnerCircleReceipts($limit);
} catch (Throwable $e) {
    fwrite(STDERR, 'Reconcile bootstrap failed: ' . $e->getMessage() . PHP_EOL);
    exit(1);
}

$now = new DateTimeImmutable('now');
$totals = [
    'noop' => 0,
    'extend' => 0,
    'cancel_persist' => 0,
    'cancel_revoke' => 0,
    'api_error' => 0,
];
$authFailure = false;

echo sprintf(
    'Reconcile: %d active Inner Circle receipt(s)%s (limit=%d).%s',
    count($receipts),
    $dryRun ? ' [DRY RUN]' : '',
    $limit,
    PHP_EOL,
);

foreach ($receipts as $index => $member) {
    if ($index > 0 && $sleepMs > 0) {
        usleep($sleepMs * 1000); // rate-limit friendly spacing between ClickBank API calls
    }

    $receipt = $member['receipt'];
    $before = $member['accessUntil'];

    try {
        $response = $http->request('GET', 'https://api.clickbank.com/rest/1.3/orders2/' . rawurlencode($receipt), [
            'headers' => [
                'Authorization' => $devKey . ':' . $apiKey,
                'Accept' => 'application/json',
            ],
        ]);
    } catch (Throwable $e) {
        ++$totals['api_error'];
        printLine($receipt, 'ERROR', 'api_error', 'request-failed', $before, $before, false);
        $logger->logReconcile($receipt, 'ERROR', 'api_error', 'request-failed', $before, $before, false);
        continue;
    }

    $httpStatus = $response->getStatusCode();
    if ($httpStatus === 401 || $httpStatus === 403) {
        $authFailure = true;
        ++$totals['api_error'];
        printLine($receipt, 'AUTH', 'api_error', 'http-' . $httpStatus, $before, $before, false);
        $logger->logReconcile($receipt, 'AUTH', 'api_error', 'http-' . $httpStatus, $before, $before, false);
        break; // bad credentials: stop hammering ClickBank, surface a nonzero exit below.
    }
    if ($httpStatus < 200 || $httpStatus >= 300) {
        ++$totals['api_error'];
        printLine($receipt, 'HTTP' . $httpStatus, 'api_error', 'http-' . $httpStatus, $before, $before, false);
        $logger->logReconcile($receipt, 'HTTP' . $httpStatus, 'api_error', 'http-' . $httpStatus, $before, $before, false);
        continue;
    }

    try {
        $decoded = json_decode((string) $response->getBody(), true, 512, JSON_THROW_ON_ERROR);
    } catch (JsonException) {
        $decoded = null;
    }
    if (!is_array($decoded)) {
        ++$totals['api_error'];
        printLine($receipt, 'BADJSON', 'api_error', 'unparseable-body', $before, $before, false);
        $logger->logReconcile($receipt, 'BADJSON', 'api_error', 'unparseable-body', $before, $before, false);
        continue;
    }

    $apiState = SubscriptionApiState::fromOrderResponse($decoded);
    $decision = $reconciler->decide($apiState, $before, $now);

    $after = $before;
    if (!$dryRun && $decision->targetAccessUntil !== null) {
        $written = $purchases->setInnerCircleAccessUntilTo(
            $member['leadId'],
            InnerCircleSkus::RECURRING,
            $decision->targetAccessUntil,
            $decision->extendOnly,
        );
        $after = $written ?? $before;
    } elseif ($decision->targetAccessUntil !== null) {
        $after = $decision->targetAccessUntil; // dry-run: show what would be written
    }

    if (!$dryRun && $decision->fireRevoke) {
        // Reuse the INS path's notifier so the Worker webhook (body + HMAC) is identical.
        $notifier->notifyRevoked($member['email'], 'cancelled', $receipt, $after);
    }

    $totals[$decision->action] = ($totals[$decision->action] ?? 0) + 1;
    printLine($receipt, $apiState->status, $decision->action, $decision->reason, $before, $after, $decision->fireRevoke);
    $logger->logReconcile($receipt, $apiState->status, $decision->action, $decision->reason, $before, $after, $decision->fireRevoke);
}

$scanned = count($receipts);
$logger->logReconcileSummary($scanned, $totals);

echo sprintf(
    'Done. scanned=%d noop=%d extend=%d cancel_persist=%d cancel_revoke=%d api_error=%d%s%s',
    $scanned,
    $totals['noop'],
    $totals['extend'],
    $totals['cancel_persist'],
    $totals['cancel_revoke'],
    $totals['api_error'],
    $dryRun ? ' [DRY RUN: no writes]' : '',
    PHP_EOL,
);

if ($authFailure) {
    fwrite(STDERR, 'ClickBank auth failed (check CLICKBANK_DEV_KEY / CLICKBANK_API_KEY).' . PHP_EOL);
    exit(2);
}

exit(0);

/**
 * Prints one per-receipt summary line to STDOUT (receipt only, never the buyer email).
 */
function printLine(
    string $receipt,
    string $apiStatus,
    string $action,
    string $reason,
    ?string $before,
    ?string $after,
    bool $fireRevoke,
): void {
    echo sprintf(
        '  %-24s api=%-8s action=%-14s revoke=%s before=%s after=%s (%s)%s',
        $receipt,
        $apiStatus,
        $action,
        $fireRevoke ? 'yes' : 'no',
        $before ?? 'null',
        $after ?? 'null',
        $reason,
        PHP_EOL,
    );
}

function reconcileEnv(string $key): string
{
    $v = $_ENV[$key] ?? getenv($key);

    return is_string($v) ? trim($v) : '';
}
