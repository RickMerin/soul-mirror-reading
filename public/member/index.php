<?php
declare(strict_types=1);

use App\Config\AppConfig;
use App\Infrastructure\DatabaseConnection;
use App\Repository\LeadRepository;
use App\Repository\PurchaseRepository;
use App\Repository\ReadingDeliveryRepository;
use App\Services\MemberUrlBuilder;
use App\Services\S3ReadingStorage;

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
    $mirrorBlockSlug = trim((string) ($lead['mirror_block_slug'] ?? ''));
    $mirrorBlockLabel = match ($mirrorBlockSlug) {
        'not-yet-ready' => 'The Not Yet Ready Block',
        'waiting-to-end' => 'Waiting for the Good Thing to End',
        'too-much' => 'Too Much / Making Yourself Smaller',
        'cannot-receive-help' => 'Cannot Let Others Help',
        default => 'Your Mirror Block',
    };

    $fallbackReadingPdfUrl = memberEnvString('MEMBER_URL_READING_PDF', '#');

    $deliveries = new ReadingDeliveryRepository($pdo);
    $delivery = $deliveries->findByLeadId($leadId);
    $readingPdfUrl = '#';
    $readingDownloadUrl = '#';
    $readingPendingMessage = 'Your personalized reading is being prepared. Check back soon — it usually takes a few minutes after purchase.';
    $hasSavedPdf = false;

    if ($delivery !== null) {
        $s3Key = (string) ($delivery['s3_object_key'] ?? '');
        if ($s3Key !== '') {
            $storage = new S3ReadingStorage($config);
            if ($storage->isConfigured()) {
                $readingPdfUrl = $storage->createPresignedDownloadUrl($s3Key, 3600);
                $readingDownloadUrl = MemberUrlBuilder::apiPath('member-reading-pdf.php');
                $hasSavedPdf = true;
                $readingPendingMessage = '';
            }
        }
    }

    if (!$hasSavedPdf && $fallbackReadingPdfUrl !== '#') {
        $readingPdfUrl = $fallbackReadingPdfUrl;
        $readingDownloadUrl = $fallbackReadingPdfUrl;
        $readingPendingMessage = '';
    }

    $readingReady = $readingPdfUrl !== '#';

    // Aligns with upsell-1.php (`cbitems=srp-1`); override via .env for downsell SKU or multi-product setups.
    $ritualSku = memberEnvString('MEMBER_RITUAL_SKU', 'srp-1');
    $ritualUnlocked = $ritualSku !== '' && $purchases->leadHasApprovedPurchaseWithItemSku($leadId, $ritualSku);

    $h = static function (string $s): string {
        return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    };

    $otoUrl = memberEnvString('MEMBER_OTO_CHECKOUT_URL', '#');

    $guardPath = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'vturb-player-guard.min.js';
    $guardVer = is_file($guardPath) ? (string) filemtime($guardPath) : (string) time();

    $replacements = [
        '{{RITUAL_WELCOME_CARD_MOD}}' => $ritualUnlocked ? ' is-ritual-unlocked' : '',
        '{{NAME}}' => $h($fullName),
        '{{FIRST_NAME}}' => $h($firstName),
        '{{INITIALS}}' => $h($initials),
        '{{MIRROR_BLOCK}}' => $h($mirrorBlockLabel),
        '{{LOGOUT_URL}}' => $h(MemberUrlBuilder::logoutPath()),
        '{{CLKBANK_NOTICE}}' => $h(memberEnvString('MEMBER_CLKBANK_NOTICE', 'Billing: CLKBANK · REBORNF')),
        '{{MEMBER_URL_READING_PDF}}' => $h($readingPdfUrl),
        '{{MEMBER_URL_READING_DOWNLOAD}}' => $h($readingDownloadUrl),
        '{{READING_PENDING_MESSAGE}}' => $h($readingPendingMessage),
        '{{READING_READY_JS}}' => $readingReady ? 'true' : 'false',
        '{{READING_READY_ATTR}}' => $readingReady ? '' : 'aria-disabled="true" tabindex="-1"',
        '{{READING_PENDING_MESSAGE_JS}}' => json_encode(
            $readingPendingMessage !== '' ? $readingPendingMessage : 'Your reading is not available yet.',
            JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR,
        ),
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
        '{{VTURB_GUARD_VER}}' => $guardVer,
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
