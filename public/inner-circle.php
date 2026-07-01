<?php

declare(strict_types=1);

use App\Config\AppConfig;
use App\Infrastructure\DatabaseConnection;
use App\Repository\LeadRepository;
use App\Repository\PurchaseRepository;

/**
 * The Inner Circle access bridge.
 *
 * ClickBank's Thank-You URL for ic-1 points here with ?cbreceipt={receipt}&email={email}.
 * We confirm the buyer actually owns ic-1, mint a short HMAC gating token (the same format the
 * Cloudflare Worker verifies), and redirect into the chat already activated. Returning visitors
 * with no receipt are sent straight to the chat, where they log in by email.
 *
 * Config (.env):
 *   IC_WORKER_URL   the deployed Worker origin (no trailing slash)
 *   IC_HMAC_SECRET  the SAME secret set on the Worker via `wrangler secret put HMAC_SECRET`
 */

$projectRoot = dirname(__DIR__);
require $projectRoot . '/vendor/autoload.php';

function icEnv(string $key, string $default = ''): string
{
    $v = $_ENV[$key] ?? getenv($key);

    return is_string($v) && $v !== '' ? $v : $default;
}

$config = AppConfig::load($projectRoot);

$workerUrl = rtrim(icEnv('IC_WORKER_URL', 'https://inner-circle-luna.inner-circle.workers.dev'), '/');
$secret    = icEnv('IC_HMAC_SECRET');

$receipt = trim((string) ($_GET['cbreceipt'] ?? $_GET['receipt'] ?? ''));
$email   = strtolower(trim((string) ($_GET['email'] ?? $_GET['cemail'] ?? '')));

/** Send the visitor to the chat, optionally already activated. */
function icRedirect(string $workerUrl, array $params = []): never
{
    $url = $workerUrl . '/' . ($params === [] ? '' : ('?' . http_build_query($params)));
    header('Location: ' . $url, true, 302);
    exit;
}

// Returning visitor (or missing config): go to the chat and log in by email there.
if ($receipt === '' || $email === '' || $secret === '' || !$config->hasDatabaseConfig()
    || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    icRedirect($workerUrl);
}

// First access from the Thank-You URL: confirm this buyer actually owns ic-1.
$firstName = '';
$verified = false;
try {
    $pdo = DatabaseConnection::fromConfig($config);
    $purchases = new PurchaseRepository($pdo);
    $leads = new LeadRepository($pdo);

    $leadId = $leads->findIdByEmail($email);
    $receiptKnown = $purchases->findIdByReceipt($receipt) !== null;

    $ownsInnerCircle = false;
    if ($leadId !== null) {
        foreach (['tic-1', 'tic-1-ds', 'ic-1', 'ic-1-ds'] as $icSku) {
            if ($purchases->leadHasApprovedPurchaseWithItemSku($leadId, $icSku)) { $ownsInnerCircle = true; break; }
        }
    }
    if ($leadId !== null && $receiptKnown && $ownsInnerCircle) {
        $verified = true;
        $stmt = $pdo->prepare('SELECT name FROM leads WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $leadId]);
        $name = trim((string) ($stmt->fetchColumn() ?: ''));
        if ($name !== '') {
            $firstName = (string) (preg_split('/\s+/', $name)[0] ?? '');
        }
    }
} catch (Throwable) {
    // Fall through to the email-login fallback below.
}

if (!$verified) {
    // No qualifying ic-1 purchase found: let the chat's email login handle it
    // (covers buyers already activated via the ClickBank INS webhook).
    icRedirect($workerUrl);
}

// Mint the gating token. Must match the Worker's verifyGating:
//   base64url(email) + "." + hex( hmac_sha256(secret, receipt + "|" + email) )
$b64   = rtrim(strtr(base64_encode($email), '+/', '-_'), '=');
$mac   = hash_hmac('sha256', $receipt . '|' . $email, $secret);
$token = $b64 . '.' . $mac;

$params = ['t' => $token, 'r' => $receipt];
if ($firstName !== '') {
    $params['n'] = $firstName;
}

icRedirect($workerUrl, $params);
