#!/usr/bin/env php
<?php

declare(strict_types=1);

/**
 * Cancel-INS fire drill (CLI only): proves the whole revocation chain end to end today, without
 * ClickBank. It builds a realistic INS v7 notification for a real receipt/sku/buyer, encrypts it
 * EXACTLY the way public/api/clickbank-ins.php decrypts it, POSTs it to that endpoint, then prints
 * the HTTP result and the resulting DB state so the operator can see PHP -> DB -> revocation
 * notifier -> Worker -> Slack fire in real time.
 *
 * Usage (from project root):
 *   php scripts/drill-cancel-ins.php <receipt> <sku> [--txn=CANCEL-TEST-REBILL] [--email=buyer@x.com] [--target=URL]
 *
 * Defaults: transactionType=CANCEL-TEST-REBILL (maps to a soft cancel), buyer email looked up from
 * the DB by receipt, target=INS_DRILL_TARGET_URL or https://soulmirrorreading.com/api/clickbank-ins.php.
 *
 * Requires CLICKBANK_SECRET_KEY in the environment (same key the endpoint decrypts with).
 *
 * WARNING: against the live target this really cancels that member's Inner Circle access (soft
 * cancel: access kept until the paid-through end). Use a TEST receipt or your own account.
 */

use App\Config\AppConfig;
use App\Infrastructure\DatabaseConnection;
use App\Repository\PurchaseRepository;
use GuzzleHttp\Client;

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit(1);
}

$projectRoot = dirname(__DIR__);
require $projectRoot . '/vendor/autoload.php';

[$receipt, $sku, $txnType, $emailOverride, $targetOverride] = drillParseArgs($argv);

if ($receipt === '' || $sku === '') {
    fwrite(STDERR, 'Usage: php scripts/drill-cancel-ins.php <receipt> <sku> [--txn=CANCEL-TEST-REBILL] [--email=buyer@x.com] [--target=URL]' . PHP_EOL);
    exit(1);
}

$secretKey = drillEnv('CLICKBANK_SECRET_KEY');
if ($secretKey === '') {
    fwrite(STDERR, 'Missing CLICKBANK_SECRET_KEY (the endpoint cannot be exercised without the shared key).' . PHP_EOL);
    exit(1);
}

$config = AppConfig::load($projectRoot);
$targetUrl = $targetOverride !== '' ? $targetOverride : drillEnv('INS_DRILL_TARGET_URL');
if ($targetUrl === '') {
    $targetUrl = 'https://soulmirrorreading.com/api/clickbank-ins.php';
}

$pdo = null;
if ($config->hasDatabaseConfig()) {
    try {
        $pdo = DatabaseConnection::fromConfig($config);
    } catch (Throwable $e) {
        fwrite(STDERR, 'Warning: DB connection failed (' . $e->getMessage() . '); readback will be skipped.' . PHP_EOL);
    }
}
$purchases = $pdo !== null ? new PurchaseRepository($pdo) : null;

$email = $emailOverride;
if ($email === '' && $purchases !== null) {
    $found = $purchases->findPurchaseWithBuyerByReceipt($receipt);
    $email = $found['email'] ?? '';
}
if ($email === '') {
    fwrite(STDERR, 'No buyer email found for receipt "' . $receipt . '". Pass --email=buyer@example.com or run where the DB is reachable.' . PHP_EOL);
    exit(1);
}

$payload = [
    'receipt' => $receipt,
    'transactionType' => $txnType,
    'transactionTime' => (new DateTimeImmutable('now'))->format(DateTimeInterface::ATOM),
    'currency' => 'USD',
    'totalOrderAmount' => 0,
    'vendor' => 'rebornf',
    'version' => '7.0',
    'customer' => [
        'billing' => [
            'email' => $email,
            'fullName' => 'Drill Buyer',
        ],
    ],
    'lineItems' => [
        [
            'itemNo' => $sku,
            'sku' => $sku,
            'productTitle' => 'The Inner Circle',
            'quantity' => 1,
            'recurring' => true,
        ],
    ],
];

$json = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);
$envelope = drillEncrypt($json, $secretKey);

echo '== Cancel-INS fire drill ==' . PHP_EOL;
echo 'Target:      ' . $targetUrl . PHP_EOL;
echo 'Receipt:     ' . $receipt . PHP_EOL;
echo 'SKU:         ' . $sku . PHP_EOL;
echo 'Txn type:    ' . $txnType . PHP_EOL;
echo 'Buyer:       ' . drillMaskEmail($email) . PHP_EOL;
echo PHP_EOL;

try {
    $http = new Client($config->guzzleClientConfig());
    $response = $http->request('POST', $targetUrl, [
        'headers' => ['Content-Type' => 'application/json'],
        'body' => $envelope,
    ]);
    echo 'HTTP status: ' . $response->getStatusCode() . PHP_EOL;
    echo 'Response:    ' . trim((string) $response->getBody()) . PHP_EOL;
} catch (Throwable $e) {
    fwrite(STDERR, 'POST failed: ' . $e->getMessage() . PHP_EOL);
    exit(1);
}

echo PHP_EOL . '== Resulting DB state for receipt ==' . PHP_EOL;
if ($purchases === null) {
    echo '(DB not configured locally; check the server DB for receipt ' . $receipt . ')' . PHP_EOL;
    exit(0);
}

// The endpoint replies then runs side effects after flushing; give it a beat before reading back.
usleep(750000);
$state = $purchases->findPurchaseWithBuyerByReceipt($receipt);
if ($state === null) {
    echo 'No purchase row found for receipt ' . $receipt . '.' . PHP_EOL;

    exit(0);
}

echo 'status:       ' . ($state['status'] !== '' ? $state['status'] : '(none)') . PHP_EOL;
echo 'txn_type:     ' . ($state['txnType'] ?? '(none)') . PHP_EOL;
echo 'access_until: ' . ($state['accessUntil'] ?? '(none / immediate)') . PHP_EOL;

exit(0);

/**
 * Encrypts the notification JSON exactly the way public/api/clickbank-ins.php's decryptNotification
 * inverts it: AES-256-CBC with key = substr(sha1(secret), 0, 32), a random 16-byte IV, ciphertext
 * and IV base64-encoded inside a {notification, iv} JSON envelope.
 */
function drillEncrypt(string $json, string $secretKey): string
{
    $key = substr(sha1($secretKey), 0, 32);
    $ivRaw = random_bytes(16);
    $ciphertext = openssl_encrypt($json, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $ivRaw);
    if (!is_string($ciphertext)) {
        fwrite(STDERR, 'Encryption failed.' . PHP_EOL);
        exit(1);
    }

    return json_encode([
        'notification' => base64_encode($ciphertext),
        'iv' => base64_encode($ivRaw),
    ], JSON_THROW_ON_ERROR);
}

/**
 * @param list<string> $argv
 * @return array{0: string, 1: string, 2: string, 3: string, 4: string} receipt, sku, txnType, email, target
 */
function drillParseArgs(array $argv): array
{
    $positional = [];
    $txnType = 'CANCEL-TEST-REBILL';
    $email = '';
    $target = '';

    foreach (array_slice($argv, 1) as $arg) {
        if (str_starts_with($arg, '--txn=')) {
            $txnType = trim(substr($arg, 6));
        } elseif (str_starts_with($arg, '--email=')) {
            $email = strtolower(trim(substr($arg, 8)));
        } elseif (str_starts_with($arg, '--target=')) {
            $target = trim(substr($arg, 9));
        } elseif (!str_starts_with($arg, '--')) {
            $positional[] = $arg;
        }
    }

    return [
        trim($positional[0] ?? ''),
        trim($positional[1] ?? ''),
        $txnType !== '' ? $txnType : 'CANCEL-TEST-REBILL',
        $email,
        $target,
    ];
}

function drillMaskEmail(string $email): string
{
    $at = strpos($email, '@');
    if ($at === false || $at < 1) {
        return '***';
    }
    $local = substr($email, 0, $at);
    $domain = substr($email, $at);
    $head = substr($local, 0, 1);

    return $head . str_repeat('*', max(1, strlen($local) - 1)) . $domain;
}

function drillEnv(string $key): string
{
    $v = $_ENV[$key] ?? getenv($key);

    return is_string($v) ? trim($v) : '';
}
