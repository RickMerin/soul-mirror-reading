<?php
declare(strict_types=1);

use App\Config\AppConfig;
use App\Infrastructure\DatabaseConnection;
use App\Repository\LeadRepository;
use App\Repository\PurchaseRepository;
use App\Services\MemberUrlBuilder;

$projectRoot = dirname(__DIR__, 2);
require $projectRoot . '/vendor/autoload.php';

/**
 * @return non-empty-string
 */
function memberEnvString(string $key, string $default = ''): string
{
    $v = $_ENV[$key] ?? getenv($key);
    if (is_string($v) && trim($v) !== '') {
        return trim($v);
    }

    return $default;
}

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

    $templatePath = __DIR__ . DIRECTORY_SEPARATOR . 'index.html';
    if (!is_readable($templatePath)) {
        throw new RuntimeException('Member template missing: ' . $templatePath);
    }
    $html = (string) file_get_contents($templatePath);
    $fullName = (string) ($lead['name'] ?? 'Member');
    $initials = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $fullName), 0, 2));
    if ($initials === '') {
        $initials = 'SM';
    }

    $firstName = explode(' ', trim($fullName))[0] ?? $fullName;
    $mirrorBlockLabel = 'The Not Yet Ready Block';

    // Aligns with upsell-1.php (`cbitems=srp-1`); override via .env for downsell SKU or multi-product setups.
    $ritualSku = memberEnvString('MEMBER_RITUAL_SKU', 'srp-1');
    $ritualUnlocked = $ritualSku !== '' && $purchases->leadHasApprovedPurchaseWithItemSku($leadId, $ritualSku);

    $h = static function (string $s): string {
        return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    };

    $otoUrl = memberEnvString('MEMBER_OTO_CHECKOUT_URL', '#');

    $replacements = [
        '{{NAME}}' => $h($fullName),
        '{{FIRST_NAME}}' => $h($firstName),
        '{{INITIALS}}' => $h($initials),
        '{{MIRROR_BLOCK}}' => $h($mirrorBlockLabel),
        '{{LOGOUT_URL}}' => $h(MemberUrlBuilder::logoutPath()),
        '{{CLKBANK_NOTICE}}' => $h(memberEnvString('MEMBER_CLKBANK_NOTICE', 'Billing: CLKBANK · REBORNF')),
        '{{MEMBER_URL_READING_PDF}}' => $h(memberEnvString('MEMBER_URL_READING_PDF', '#')),
        '{{MEMBER_URL_BONUS_COMPANION}}' => $h(memberEnvString('MEMBER_URL_BONUS_COMPANION', '#')),
        '{{MEMBER_URL_BONUS_SHIFT_TRACKER}}' => $h(memberEnvString('MEMBER_URL_BONUS_SHIFT_TRACKER', '#')),
        '{{MEMBER_URL_BONUS_ROOT_CAUSE}}' => $h(memberEnvString('MEMBER_URL_BONUS_ROOT_CAUSE', '#')),
        '{{MEMBER_URL_BONUS_MEDITATION_AUDIO}}' => $h(memberEnvString('MEMBER_URL_BONUS_MEDITATION_AUDIO', '')),
        '{{MEMBER_URL_RITUAL_REPORT1}}' => $h(memberEnvString('MEMBER_URL_RITUAL_REPORT1', '#')),
        '{{MEMBER_URL_RITUAL_REPORT2}}' => $h(memberEnvString('MEMBER_URL_RITUAL_REPORT2', '#')),
        '{{MEMBER_URL_RITUAL_REPORT3}}' => $h(memberEnvString('MEMBER_URL_RITUAL_REPORT3', '#')),
        '{{MEMBER_URL_RITUAL_WORKBOOK}}' => $h(memberEnvString('MEMBER_URL_RITUAL_WORKBOOK', '#')),
        '{{MEMBER_OTO_CHECKOUT_URL}}' => $h($otoUrl),
        '{{RITUAL_UNLOCKED_JS}}' => $ritualUnlocked ? 'true' : 'false',
    ];
    $rendered = strtr($html, $replacements);

    if (stripos($rendered, 'rel="icon"') === false) {
        $rendered = str_replace('</head>', '  <link rel="icon" type="image/svg+xml" href="/favicon.svg">' . PHP_EOL . '</head>', $rendered);
    }

    header('Content-Type: text/html; charset=utf-8');
    echo $rendered;
} catch (Throwable) {
    http_response_code(500);
    echo 'Unable to load member area.';
}
