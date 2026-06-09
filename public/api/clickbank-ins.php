<?php
declare(strict_types=1);

use App\Config\AppConfig;
use App\Infrastructure\DatabaseConnection;
use App\Repository\LeadRepository;
use App\Repository\PurchaseRepository;
use App\Services\KitService;
use App\Services\ReadingDeliveryTrigger;
use App\Services\SlackClickBankInsLogger;
use GuzzleHttp\Client;

$projectRoot = dirname(__DIR__, 2);
require $projectRoot . '/vendor/autoload.php';

header('Content-Type: application/json; charset=utf-8');

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    echo json_encode(['error' => 'Method not allowed.'], JSON_UNESCAPED_UNICODE);
    exit;
}

$raw = file_get_contents('php://input');
if ($raw === false) {
    $raw = '';
}
if (strlen($raw) > 262144) {
    http_response_code(413);
    echo json_encode(['error' => 'Payload too large.'], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    $wrapper = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
} catch (JsonException) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON payload.'], JSON_UNESCAPED_UNICODE);
    exit;
}

if (!is_array($wrapper)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON payload.'], JSON_UNESCAPED_UNICODE);
    exit;
}

$encrypted = $wrapper['notification'] ?? null;
$iv = $wrapper['iv'] ?? null;
if (!is_string($encrypted) || $encrypted === '' || !is_string($iv) || $iv === '') {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required INS envelope fields.'], JSON_UNESCAPED_UNICODE);
    exit;
}

$config = AppConfig::load($projectRoot);
$secretKey = $_ENV['CLICKBANK_SECRET_KEY'] ?? getenv('CLICKBANK_SECRET_KEY') ?: '';
if (!is_string($secretKey) || $secretKey === '') {
    http_response_code(500);
    error_log('clickbank-ins.php missing CLICKBANK_SECRET_KEY');
    echo json_encode(['error' => 'INS handler is not configured.'], JSON_UNESCAPED_UNICODE);
    exit;
}
if (!$config->hasDatabaseConfig()) {
    http_response_code(500);
    error_log('clickbank-ins.php database config missing');
    echo json_encode(['error' => 'INS handler is not configured.'], JSON_UNESCAPED_UNICODE);
    exit;
}

$decrypted = decryptNotification($encrypted, $iv, $secretKey);
if ($decrypted === null) {
    http_response_code(400);
    echo json_encode(['error' => 'Unable to decrypt notification.'], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    $payload = json_decode($decrypted, true, 512, JSON_THROW_ON_ERROR);
} catch (JsonException) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid decrypted notification JSON.'], JSON_UNESCAPED_UNICODE);
    exit;
}
if (!is_array($payload)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid decrypted notification JSON.'], JSON_UNESCAPED_UNICODE);
    exit;
}

$email = extractBuyerEmail($payload);
if ($email === null) {
    http_response_code(400);
    echo json_encode(['error' => 'Buyer email is required in INS payload.'], JSON_UNESCAPED_UNICODE);
    exit;
}
$receipt = extractReceipt($payload);
if ($receipt === null) {
    http_response_code(400);
    echo json_encode(['error' => 'Receipt is required in INS payload.'], JSON_UNESCAPED_UNICODE);
    exit;
}

$items = extractItems($payload);
$status = normalizeStatus($payload);
$txnType = extractTxnType($payload);

try {
    $pdo = DatabaseConnection::fromConfig($config);
    $leads = new LeadRepository($pdo);
    $purchases = new PurchaseRepository($pdo);

    $leadId = $leads->findOrCreateMinimalByEmail($email, extractBuyerName($payload));
    $purchases->upsertByReceipt(
        $leadId,
        $receipt,
        $txnType,
        $status,
        extractCurrency($payload),
        extractAmount($payload),
        $items,
        $payload
    );
    $purchaseId = $purchases->findIdByReceipt($receipt);
} catch (Throwable $e) {
    error_log('clickbank-ins.php persistence failed: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Unable to persist notification.'], JSON_UNESCAPED_UNICODE);
    exit;
}

if ($config->clickbankInsSlackWebhookUrl !== '') {
    try {
        $slackHttp = new Client($config->guzzleClientConfig());
        (new SlackClickBankInsLogger($config, $slackHttp))->notify($payload, $txnType, $receipt);
    } catch (Throwable $e) {
        error_log('clickbank-ins.php Slack notify failed: ' . $e->getMessage());
    }
}

if (clickbankPurchaseShouldTagBuyerInKit($status) && $config->kitApiKey !== '') {
    try {
        $http = new Client($config->guzzleClientConfig());
        (new KitService($config, $http))->subscribeClickBankBuyer($email, $items);
    } catch (Throwable $e) {
        error_log('clickbank-ins.php Kit subscribe failed: ' . $e->getMessage());
    }
}

if (isset($purchaseId) && is_int($purchaseId) && $purchaseId > 0
    && ReadingDeliveryTrigger::shouldDeliverForApprovedPurchase($status, $items)) {
    (new ReadingDeliveryTrigger($projectRoot))->queuePurchaseDelivery($purchaseId);
}

http_response_code(200);
echo json_encode(['ok' => true], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);

/**
 * Decrypts the ClickBank notification using AES-256-CBC.
 *
 * @param string $encrypted The base64 encoded ciphertext.
 * @param string $iv The base64 encoded initialization vector.
 * @param string $secretKey The secret key used for decryption.
 * @return string|null Returns the decrypted string, or null on failure.
 */
function decryptNotification(string $encrypted, string $iv, string $secretKey): ?string
{
    $ciphertext = base64_decode($encrypted, true);
    $ivRaw = base64_decode($iv, true);
    if (!is_string($ciphertext) || !is_string($ivRaw)) {
        return null;
    }

    $decrypted = openssl_decrypt(
        $ciphertext,
        'AES-256-CBC',
        substr(sha1($secretKey), 0, 32),
        OPENSSL_RAW_DATA,
        $ivRaw
    );
    if (!is_string($decrypted) || $decrypted === '') {
        return null;
    }

    return trim($decrypted, "\0..\32");
}

/**
 * Extracts the buyer's email from various possible locations in the payload.
 *
 * @param array $payload The notification payload.
 * @return string|null The buyer email in lowercase, or null if missing/invalid.
 */
function extractBuyerEmail(array $payload): ?string
{
    $email = payloadValue($payload, [
        'customer.billing.email',
        'customer.email',
        'billing.email',
        'email',
    ]);
    if (!is_string($email)) {
        return null;
    }
    $email = trim($email);
    if ($email === '' || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        return null;
    }

    return strtolower($email);
}

/**
 * Tries to extract a buyer's name from the payload, falling back to ClickBank Buyer if not found.
 *
 * @param array $payload The notification payload.
 * @return string The buyer's full name or a default string.
 */
function extractBuyerName(array $payload): string
{
    $fullName = payloadValue($payload, ['customer.fullName', 'billing.fullName', 'fullName']);
    if (is_string($fullName) && trim($fullName) !== '') {
        return trim($fullName);
    }
    $first = payloadValue($payload, ['customer.billing.firstName', 'customer.firstName', 'billing.firstName']);
    $last = payloadValue($payload, ['customer.billing.lastName', 'customer.lastName', 'billing.lastName']);
    if (is_string($first) || is_string($last)) {
        $name = trim(sprintf('%s %s', (string) $first, (string) $last));
        if ($name !== '') {
            return $name;
        }
    }

    return 'ClickBank Buyer';
}

/**
 * Extracts the receipt value from various possible locations in the payload.
 *
 * @param array $payload The notification payload.
 * @return string|null The (possibly truncated) receipt, or null.
 */
function extractReceipt(array $payload): ?string
{
    $receipt = payloadValue($payload, [
        'receipt',
        'order.receipt',
        'transaction.receipt',
        'transactionId',
        'txnId',
    ]);
    if (!is_string($receipt)) {
        return null;
    }
    $receipt = trim($receipt);

    return $receipt !== '' ? substr($receipt, 0, 120) : null;
}

/**
 * Extracts transaction type from the payload and normalizes it to uppercase.
 *
 * @param array $payload The notification payload.
 * @return string|null The transaction type, or null if not found.
 */
function extractTxnType(array $payload): ?string
{
    $txnType = payloadValue($payload, ['transactionType', 'txnType', 'transType', 'eventType']);
    if (!is_string($txnType) || trim($txnType) === '') {
        return null;
    }

    return strtoupper(trim($txnType));
}

/**
 * Determines a normalized purchase status from the payload or transaction type.
 *
 * @param array $payload The notification payload.
 * @return string The status (approved, refunded, etc.).
 */
function normalizeStatus(array $payload): string
{
    $status = payloadValue($payload, ['status', 'order.orderStatus']);
    if (is_string($status) && trim($status) !== '') {
        return strtolower(trim($status));
    }
    $txnType = extractTxnType($payload);

    return match ($txnType) {
        'SALE', 'BILL', 'BILLED', 'TEST_SALE' => 'approved',
        'RFND', 'REFUND' => 'refunded',
        'CGBK', 'CHARGEBACK' => 'chargeback',
        'CANCEL-REBILL' => 'cancelled',
        default => 'pending',
    };
}

/**
 * Extracts the purchase currency code (e.g., USD) from payload.
 *
 * @param array $payload The notification payload.
 * @return string|null The ISO currency code or null.
 */
function extractCurrency(array $payload): ?string
{
    $currency = payloadValue($payload, ['currency', 'order.currency']);
    if (!is_string($currency)) {
        return null;
    }
    $currency = strtoupper(trim($currency));
    if ($currency === '' || strlen($currency) > 3) {
        return null;
    }

    return $currency;
}

/**
 * Extracts the order amount from payload and normalizes to float.
 *
 * @param array $payload The notification payload.
 * @return float|null The order amount or null.
 */
function extractAmount(array $payload): ?float
{
    $amount = payloadValue($payload, [
        'totalOrderAmount',
        'order.totalOrderAmount',
        'order.totalAccountAmount',
        'amount',
    ]);
    if (is_numeric($amount)) {
        return round((float) $amount, 2);
    }

    return null;
}

/**
 * Determines if the ClickBank purchase status should cause buyer to be tagged/added in Kit.
 *
 * @param string $status The purchase status.
 * @return bool True if buyer should be tagged in Kit, false otherwise.
 */
function clickbankPurchaseShouldTagBuyerInKit(string $status): bool
{
    return in_array(strtolower(trim($status)), ['approved', 'complete', 'completed', 'active'], true);
}

/**
 * Extracts array of line items/products from various possible locations in the payload.
 *
 * @param array $payload The notification payload.
 * @return array<int,array<string,mixed>> The items array.
 */
function extractItems(array $payload): array
{
    $items = payloadValue($payload, ['lineItems', 'order.lineItems', 'items']);
    if (!is_array($items)) {
        return [];
    }

    return array_values(array_filter($items, static fn ($item): bool => is_array($item)));
}

/**
 * Attempts to extract value from payload by searching multiple dotted paths.
 *
 * @param array $payload The notification payload.
 * @param array $paths List of dot-separated paths to search.
 * @return mixed|null The first found value, or null.
 */
function payloadValue(array $payload, array $paths): mixed
{
    foreach ($paths as $path) {
        $value = payloadValueByPath($payload, $path);
        if ($value !== null) {
            return $value;
        }
    }

    return null;
}

/**
 * Follows a single dot-separated path to extract value from a nested array.
 *
 * @param array $payload The notification payload.
 * @param string $path Dot-separated key path (e.g. "order.lineItems").
 * @return mixed|null The found value or null.
 */
function payloadValueByPath(array $payload, string $path): mixed
{
    $current = $payload;
    foreach (explode('.', $path) as $segment) {
        if (!is_array($current) || !array_key_exists($segment, $current)) {
            return null;
        }
        $current = $current[$segment];
    }

    return $current;
}
