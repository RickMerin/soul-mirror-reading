<?php
declare(strict_types=1);

use App\Config\AppConfig;
use App\Infrastructure\DatabaseConnection;
use App\Repository\LeadRepository;
use App\Repository\PurchaseRepository;
use App\Services\MemberUrlBuilder;

$projectRoot = dirname(__DIR__, 2);
require $projectRoot . '/vendor/autoload.php';

session_start();
$leadId = isset($_SESSION['member_lead_id']) ? (int) $_SESSION['member_lead_id'] : 0;
if ($leadId < 1) {
    header('Location: ' . MemberUrlBuilder::loginPath());
    exit;
}

$config = AppConfig::load($projectRoot);
if (!$config->hasDatabaseConfig()) {
    http_response_code(500);
    echo 'Member database is not configured.';
    exit;
}

try {
    $pdo = DatabaseConnection::fromConfig($config);
    $leads = new LeadRepository($pdo);
    $purchases = new PurchaseRepository($pdo);
    if (!$purchases->buyerHasAnyPurchase($leadId)) {
        $_SESSION = [];
        header('Location: ' . MemberUrlBuilder::loginPath());
        exit;
    }

    $lead = $leads->findById($leadId);
    if ($lead === null) {
        $_SESSION = [];
        header('Location: ' . MemberUrlBuilder::loginPath());
        exit;
    }

    $templatePath = $projectRoot . '/member/index.html';
    $html = (string) file_get_contents($templatePath);
    $fullName = (string) ($lead['name'] ?? 'Member');
    $initials = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $fullName), 0, 2));
    if ($initials === '') {
        $initials = 'SM';
    }

    $firstName = explode(' ', trim($fullName))[0] ?? $fullName;
    $replacements = [
        '{{NAME}}' => $fullName,
        '{{FIRST_NAME}}' => $firstName,
        '{{INITIALS}}' => $initials,
        '{{MIRROR_BLOCK}}' => 'The Not Yet Ready Block',
    ];
    $rendered = strtr($html, $replacements);
    $logoutPath = MemberUrlBuilder::logoutPath();
    $rendered = str_replace('</body>', '<p style="text-align:center;padding:20px"><a href="' . htmlspecialchars($logoutPath, ENT_QUOTES) . '">Logout</a></p></body>', $rendered);

    header('Content-Type: text/html; charset=utf-8');
    echo $rendered;
} catch (Throwable) {
    http_response_code(500);
    echo 'Unable to load member area.';
}
