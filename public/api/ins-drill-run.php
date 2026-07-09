<?php
declare(strict_types=1);
// TEMPORARY (2026-07-09) v2: self-contained token-gated cancel-INS fire drill. REMOVE after verification.
ini_set('display_errors', '1');
error_reporting(E_ALL);
header('Content-Type: text/plain; charset=utf-8');
header('Cache-Control: no-store');

$expected = 'c98ed674723b470bae495220e6f116ccd02d591624fc1f3d';
$given = $_GET['token'] ?? '';
if (!is_string($given) || !hash_equals($expected, $given)) { http_response_code(404); echo 'Not found.'; exit; }

$receipt = preg_replace('/[^A-Z0-9-]/', '', strtoupper((string)($_GET['receipt'] ?? '')));
$sku = preg_replace('/[^a-z0-9-]/', '', strtolower((string)($_GET['sku'] ?? '')));
$txnType = 'CANCEL-TEST-REBILL';
if ($receipt === '' || $sku === '') { http_response_code(400); echo 'receipt and sku required'; exit; }

use App\Config\AppConfig;
use App\Infrastructure\DatabaseConnection;
use App\Repository\PurchaseRepository;
use GuzzleHttp\Client;

$projectRoot = dirname(__DIR__, 2);
require $projectRoot . '/vendor/autoload.php';
$config = AppConfig::load($projectRoot);
$secretKey = $_ENV['CLICKBANK_SECRET_KEY'] ?? getenv('CLICKBANK_SECRET_KEY') ?: '';
if ($secretKey === '') { echo 'CLICKBANK_SECRET_KEY missing'; exit; }

$pdo = DatabaseConnection::fromConfig($config);
$purchases = new PurchaseRepository($pdo);
$found = $purchases->findPurchaseWithBuyerByReceipt($receipt);
$email = $found['email'] ?? '';
if ($email === '') { echo 'no buyer email for receipt'; exit; }

$payload = [
    'receipt' => $receipt,
    'transactionType' => $txnType,
    'transactionTime' => (new DateTimeImmutable('now'))->format(DateTimeInterface::ATOM),
    'currency' => 'USD',
    'totalOrderAmount' => 0,
    'vendor' => 'rebornf',
    'version' => '7.0',
    'customer' => ['billing' => ['email' => $email, 'fullName' => 'Drill Buyer']],
    'lineItems' => [['itemNo' => $sku, 'sku' => $sku, 'productTitle' => 'The Inner Circle', 'quantity' => 1, 'recurring' => true]],
];
$json = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);
$key = substr(sha1($secretKey), 0, 32);
$ivRaw = random_bytes(16);
$ciphertext = openssl_encrypt($json, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $ivRaw);
$envelope = json_encode(['notification' => base64_encode($ciphertext), 'iv' => base64_encode($ivRaw)], JSON_THROW_ON_ERROR);

echo "== Cancel-INS fire drill (web wrapper v2) ==\n";
echo "Receipt: $receipt | SKU: $sku | Txn: $txnType\n\n";
$http = new Client($config->guzzleClientConfig());
$response = $http->request('POST', 'https://soulmirrorreading.com/api/clickbank-ins.php', [
    'headers' => ['Content-Type' => 'application/json'],
    'body' => $envelope,
    'http_errors' => false,
]);
echo 'INS endpoint HTTP: ' . $response->getStatusCode() . "\n";
echo 'Response: ' . trim((string) $response->getBody()) . "\n\n";
usleep(900000);
$state = $purchases->findPurchaseWithBuyerByReceipt($receipt);
echo "== Resulting DB state ==\n";
echo 'status: ' . ($state['status'] ?? '?') . "\n";
echo 'access_until: ' . ($state['access_until'] ?? '(null)') . "\n";

echo "\n== Env presence (booleans only) ==\n";
$revokeUrlSet = (($_ENV['IC_REVOKE_WEBHOOK_URL'] ?? getenv('IC_REVOKE_WEBHOOK_URL') ?: '') !== '') ? 'yes' : 'NO';
$hmacSet = (($_ENV['IC_HMAC_SECRET'] ?? getenv('IC_HMAC_SECRET') ?: '') !== '') ? 'yes' : 'NO';
echo "IC_REVOKE_WEBHOOK_URL set: $revokeUrlSet\n";
echo "IC_HMAC_SECRET set: $hmacSet\n";
$logPath = $projectRoot . '/storage/logs/clickbank-ins.log';
echo "\n== Last 8 INS log lines ==\n";
if (is_file($logPath)) { foreach (array_slice(file($logPath, FILE_IGNORE_NEW_LINES), -8) as $ln) { echo $ln . "\n"; } } else { echo "(no log)\n"; }
