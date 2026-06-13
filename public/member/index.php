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

/**
 * Member-portal back-sell only (vtid=membership). Funnel upsells use cbur=a/d on upsell-*.php.
 */
function memberPortalCheckoutUrl(string $sku): string
{
    return 'https://rebornf.pay.clickbank.net/?cbitems=' . rawurlencode($sku) . '&vtid=membership';
}

function memberStripMarkedBlock(string $html, string $markerName): string
{
    $quoted = preg_quote($markerName, '#');
    $pattern = '#<!-- smr:' . $quoted . '-start -->.*?<!-- smr:' . $quoted . '-end -->#s';

    return (string) preg_replace($pattern, '', $html);
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

    // 2h reading countdown: seconds remaining since the reading became ready (DB-side, tz-safe).
    // 0 for existing buyers (ready > 2h ago) and the env-fallback path, so they see no countdown.
    $readingCountdownSeconds = ($readingReady && $hasSavedPdf)
        ? $deliveries->unlockSecondsRemaining($leadId, 7200)
        : 0;

    // Aligns with upsell-1.php (`cbitems=srp-1`); override via .env for downsell SKU or multi-product setups.
    $ritualSku = memberEnvString('MEMBER_RITUAL_SKU', 'srp-1');
    $ritualUnlocked = $ritualSku !== '' && $purchases->leadHasApprovedPurchaseWithItemSku($leadId, $ritualSku);

    // Aligns with love-clarity upsell (`cbitems=lcr-1` / `lcr-1-ds`).
    $loveClarityUnlocked = $purchases->leadHasApprovedPurchaseWithItemSku($leadId, 'lcr-1')
        || $purchases->leadHasApprovedPurchaseWithItemSku($leadId, 'lcr-1-ds');

    // Aligns with mirror-meditations upsell (`cbitems=mm-1` / `mm-1-ds`).
    $mirrorMeditationsUnlocked = $purchases->leadHasApprovedPurchaseWithItemSku($leadId, 'mm-1')
        || $purchases->leadHasApprovedPurchaseWithItemSku($leadId, 'mm-1-ds');

    $h = static function (string $s): string {
        return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    };

    // vtid=membership checkout links are rendered only here for buyers missing OTO1/OTO2.
    if ($ritualUnlocked) {
        $html = memberStripMarkedBlock($html, 'ritual-locked');
        $otoUrl = '#';
    } else {
        $otoUrl = memberEnvString('MEMBER_OTO_CHECKOUT_URL', memberPortalCheckoutUrl('srp-1'));
    }

    if ($loveClarityUnlocked) {
        $html = memberStripMarkedBlock($html, 'love-clarity-locked');
        $lcrCheckoutUrl = '#';
    } else {
        $lcrCheckoutUrl = memberEnvString('MEMBER_LCR_CHECKOUT_URL', memberPortalCheckoutUrl('lcr-1'));
    }

    if ($mirrorMeditationsUnlocked) {
        $html = memberStripMarkedBlock($html, 'mirror-meditations-locked');
        $mmCheckoutUrl = '#';
    } else {
        $mmCheckoutUrl = memberEnvString('MEMBER_MM_CHECKOUT_URL', 'https://rebornf.pay.clickbank.net/?cbitems=mm-1&vtid=membership');
    }

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
        '{{READING_COUNTDOWN_SECONDS}}' => (string) $readingCountdownSeconds,
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
        '{{MEMBER_LCR_CHECKOUT_URL}}' => $h($lcrCheckoutUrl),
        '{{MEMBER_MM_CHECKOUT_URL}}' => $h($mmCheckoutUrl),
        '{{RITUAL_UNLOCKED_JS}}' => $ritualUnlocked ? 'true' : 'false',
        '{{LOVE_CLARITY_UNLOCKED_JS}}' => $loveClarityUnlocked ? 'true' : 'false',
        '{{MIRROR_MEDITATIONS_UNLOCKED_JS}}' => $mirrorMeditationsUnlocked ? 'true' : 'false',
        '{{MEMBER_URL_LCR_GUIDE}}' => $h(memberEnvString('MEMBER_URL_LCR_GUIDE', '#')),
        '{{MEMBER_URL_LCR_PURPOSE}}' => $h(memberEnvString('MEMBER_URL_LCR_PURPOSE', '#')),
        '{{MEMBER_URL_LCR_AUDIO}}' => $h(memberEnvString('MEMBER_URL_LCR_AUDIO', '')),
        '{{MEMBER_URL_LCR_DAILY}}' => $h(memberEnvString('MEMBER_URL_LCR_DAILY', '#')),
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
