<?php
declare(strict_types=1);

use App\Config\AppConfig;
use App\Infrastructure\DatabaseConnection;
use App\Repository\LeadRepository;
use App\Repository\PurchaseRepository;

/**
 * Inner Circle entitlement check for the Cloudflare Worker.
 *
 * GET ?email={email}&sig={hex_hmac_sha256(email, IC_ENTITLEMENT_SECRET)}
 *
 * IC_ENTITLEMENT_SECRET may match IC_HMAC_SECRET when only one shared secret is configured.
 */
$projectRoot = dirname(__DIR__, 2);
require $projectRoot . '/vendor/autoload.php';

header('Content-Type: application/json; charset=utf-8');

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'GET') {
    http_response_code(405);
    header('Allow: GET');
    echo json_encode(['error' => 'Method not allowed.'], JSON_UNESCAPED_UNICODE);
    exit;
}

function icEntitlementEnv(string $key, string $default = ''): string
{
    $v = $_ENV[$key] ?? getenv($key);

    return is_string($v) && $v !== '' ? $v : $default;
}

$email = strtolower(trim((string) ($_GET['email'] ?? '')));
$sig = strtolower(trim((string) ($_GET['sig'] ?? '')));
$secret = icEntitlementEnv('IC_ENTITLEMENT_SECRET', icEntitlementEnv('IC_HMAC_SECRET'));

if ($secret === '' || $email === '' || $sig === ''
    || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request.'], JSON_UNESCAPED_UNICODE);
    exit;
}

$expected = hash_hmac('sha256', $email, $secret);
if (!hash_equals($expected, $sig)) {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden.'], JSON_UNESCAPED_UNICODE);
    exit;
}

$config = AppConfig::load($projectRoot);
if (!$config->hasDatabaseConfig()) {
    http_response_code(503);
    echo json_encode(['error' => 'Entitlement service unavailable.'], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    $pdo = DatabaseConnection::fromConfig($config);
    $leads = new LeadRepository($pdo);
    $purchases = new PurchaseRepository($pdo);

    $leadId = $leads->findIdByEmail($email);
    $active = $leadId !== null && $purchases->leadHasApprovedInnerCirclePurchase($leadId);

    http_response_code(200);
    echo json_encode(['active' => $active], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
} catch (Throwable $e) {
    error_log('inner-circle-entitlement.php failed: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Unable to check entitlement.'], JSON_UNESCAPED_UNICODE);
}
