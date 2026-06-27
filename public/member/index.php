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

// ── TEST-ONLY preview (host-gated; can never run on production) ──
$__host = strtolower((string) ($_SERVER['HTTP_HOST'] ?? ''));
$__pf = ''; $__plocked = false;
if ($__host === 'test.soulmirrorreading.com' && isset($_GET['preview'])) {
    $__p = (string) $_GET['preview'];
    if (in_array($__p, ['love', 'wealth', 'love-locked', 'wealth-locked'], true)) {
        $__plocked = (substr($__p, -7) === '-locked');
        $__pf = $__plocked ? substr($__p, 0, -7) : $__p;
    }
}
if ($__pf !== '') {
    $tpl = (string) file_get_contents(__DIR__ . '/index.html');
    $offOto2 = ($__pf === 'love') ? 'love-clarity' : 'wealth-clarity';
    $inOto2  = ($__pf === 'love') ? 'wealth-clarity' : 'love-clarity';
    $tpl = preg_replace('#<!-- smr:' . $offOto2 . '-section-start -->.*?<!-- smr:' . $offOto2 . '-section-end -->#s', '', $tpl);
    $tpl = preg_replace('#<!-- smr:' . $offOto2 . '-nav-start -->.*?<!-- smr:' . $offOto2 . '-nav-end -->#s', '', $tpl);
    foreach (['ritual', $inOto2, 'mirror-meditations'] as $pr) {
        $m = $__plocked ? ($pr . '-full') : ($pr . '-locked');
        $tpl = preg_replace('#<!-- smr:' . $m . '-start -->.*?<!-- smr:' . $m . '-end -->#s', '', $tpl);
    }
    $__pvco = $__plocked ? 'https://rebornf.pay.clickbank.net/?cbitems=upgrade&vtid=membership' : '#';
    $pv = [
        '{{FUNNEL}}' => $__pf,
        '{{NAME}}' => 'Preview ' . ucfirst($__pf) . ' Buyer',
        '{{FIRST_NAME}}' => 'Preview',
        '{{INITIALS}}' => 'PV',
        '{{MIRROR_BLOCK}}' => 'Your Mirror Block',
        '{{LOGOUT_URL}}' => '/member/logout.php',
        '{{CLKBANK_NOTICE}}' => 'PREVIEW (test only)',
        '{{MEMBER_URL_READING_PDF}}' => '#', '{{MEMBER_URL_READING_DOWNLOAD}}' => '#',
        '{{READING_PENDING_MESSAGE}}' => '', '{{READING_READY_JS}}' => 'true',
        '{{READING_COUNTDOWN_SECONDS}}' => '0', '{{READING_READY_ATTR}}' => '',
        '{{RITUAL_WELCOME_CARD_MOD}}' => $__plocked ? '' : ' is-ritual-unlocked',
        '{{RITUAL_LOVE_WELCOME_CARD_MOD}}' => '',
        '{{RITUAL_UNLOCKED_JS}}' => $__plocked ? 'false' : 'true', '{{LOVE_CLARITY_UNLOCKED_JS}}' => $__plocked ? 'false' : 'true', '{{MIRROR_MEDITATIONS_UNLOCKED_JS}}' => $__plocked ? 'false' : 'true', '{{WEALTH_CLARITY_UNLOCKED_JS}}' => $__plocked ? 'false' : 'true',
        '{{MEMBER_OTO_CHECKOUT_URL}}' => $__pvco, '{{MEMBER_OTO_LOVE_CHECKOUT_URL}}' => $__pvco,
        '{{MEMBER_LCR_CHECKOUT_URL}}' => $__pvco, '{{MEMBER_WCR_CHECKOUT_URL}}' => $__pvco, '{{MEMBER_MM_CHECKOUT_URL}}' => $__pvco,
        '{{VTURB_GUARD_VER}}' => (string) time(),
    ];
    foreach (['BONUS_COMPANION','BONUS_SHIFT_TRACKER','BONUS_ROOT_CAUSE','BONUS_MEDITATION_AUDIO',
              'RITUAL_REPORT1','RITUAL_REPORT2','RITUAL_REPORT3','RITUAL_WORKBOOK',
              'RITUAL_LOVE_REPORT1','RITUAL_LOVE_REPORT2','RITUAL_LOVE_REPORT3','RITUAL_LOVE_WORKBOOK',
              'LCR_GUIDE','LCR_PURPOSE','LCR_AUDIO','LCR_DAILY',
              'WCR_GUIDE','WCR_PURPOSE','WCR_AUDIO','WCR_DAILY'] as $k) {
        $pv['{{MEMBER_URL_' . $k . '}}'] = '#';
    }
    if (!$__plocked) { $pv['{{MEMBER_URL_WCR_GUIDE}}'] = 'https://soulmirrorreading.s3.us-west-1.amazonaws.com/upsell2/wealth-clarity-ritual-guide.pdf'; }
    $out = strtr($tpl, $pv);
    $out = preg_replace('/\{\{[A-Z_]+\}\}/', '#', $out);
    header('Content-Type: text/html; charset=utf-8');
    echo $out;
    exit;
}
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
    $ritualUnlocked = ($ritualSku !== '' && $purchases->leadHasApprovedPurchaseWithItemSku($leadId, $ritualSku))
        || $purchases->leadHasApprovedPurchaseWithItemSku($leadId, 'srp-1-l')
        || $purchases->leadHasApprovedPurchaseWithItemSku($leadId, 'srp-1-l-ds')
        || $purchases->leadHasApprovedPurchaseWithItemSku($leadId, 'srp-1-l-ds2');

    // Aligns with love-clarity upsell (`cbitems=lcr-1` / `lcr-1-ds`).
    $loveClarityUnlocked = $purchases->leadHasApprovedPurchaseWithItemSku($leadId, 'lcr-1')
        || $purchases->leadHasApprovedPurchaseWithItemSku($leadId, 'lcr-1-ds');

    // Aligns with mirror-meditations upsell (`cbitems=mm-1` / `mm-1-ds`).
    $mirrorMeditationsUnlocked = $purchases->leadHasApprovedPurchaseWithItemSku($leadId, 'mm-1')
        || $purchases->leadHasApprovedPurchaseWithItemSku($leadId, 'mm-1-ds');

    // Love-funnel delivery: which front-end did this buyer come through, and their love-set unlocks.
    $boughtLove = $purchases->leadHasApprovedPurchaseWithItemSku($leadId, 'smr-1-l');
    $boughtWealth = $purchases->leadHasApprovedPurchaseWithItemSku($leadId, 'smr-1-w')
        || $purchases->leadHasApprovedPurchaseWithItemSku($leadId, 'smr-1-wtsl');
    $funnel = ($boughtLove && !$boughtWealth) ? 'love' : (($boughtWealth && !$boughtLove) ? 'wealth' : 'all');

    $ritualLoveUnlocked = $purchases->leadHasApprovedPurchaseWithItemSku($leadId, 'srp-1-l')
        || $purchases->leadHasApprovedPurchaseWithItemSku($leadId, 'srp-1-l-ds')
        || $purchases->leadHasApprovedPurchaseWithItemSku($leadId, 'srp-1-l-ds2');
    $wealthClarityUnlocked = $purchases->leadHasApprovedPurchaseWithItemSku($leadId, 'wcr-1')
        || $purchases->leadHasApprovedPurchaseWithItemSku($leadId, 'wcr-1-ds');
    $h = static function (string $s): string {
        return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    };

    // ── Funnel + entitlement gating (server-side; non-owners NEVER receive a product's files) ──
    // Off-funnel OTO2 is removed ENTIRELY: love buyers never see Love Clarity, wealth buyers never see Wealth Clarity.
    if (($funnel === 'love') && !$loveClarityUnlocked) {
        $html = memberStripMarkedBlock($html, 'love-clarity-section');
        $html = memberStripMarkedBlock($html, 'love-clarity-nav');
    }
    if (($funnel === 'wealth') && !$wealthClarityUnlocked) {
        $html = memberStripMarkedBlock($html, 'wealth-clarity-section');
        $html = memberStripMarkedBlock($html, 'wealth-clarity-nav');
    }
    // Per product: OWNED -> strip the locked offer (show downloads); NOT OWNED -> strip the -full block
    // (removes every download URL, placeholder AND hardcoded, from the served HTML), leaving only the offer.
    foreach ([
        ['ritual', $ritualUnlocked],
        ['love-clarity', $loveClarityUnlocked],
        ['wealth-clarity', $wealthClarityUnlocked],
        ['mirror-meditations', $mirrorMeditationsUnlocked],
    ] as $pair) {
        [$pName, $pOwned] = $pair;
        $html = memberStripMarkedBlock($html, $pOwned ? ($pName . '-locked') : ($pName . '-full'));
    }

    // Upgrade checkout links shown inside the locked offers (funnel-aware where the product SKU differs).
    $ritualUpgradeSku = ($funnel === 'love') ? 'srp-1-l' : 'srp-1';
    $otoUrl = $ritualUnlocked ? '#' : memberEnvString('MEMBER_OTO_CHECKOUT_URL', memberPortalCheckoutUrl($ritualUpgradeSku));
    $lcrCheckoutUrl = $loveClarityUnlocked ? '#' : memberEnvString('MEMBER_LCR_CHECKOUT_URL', memberPortalCheckoutUrl('lcr-1'));
    $mmCheckoutUrl = $mirrorMeditationsUnlocked ? '#' : memberEnvString('MEMBER_MM_CHECKOUT_URL', 'https://rebornf.pay.clickbank.net/?cbitems=mm-1&vtid=membership');

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
        '{{WEALTH_CLARITY_UNLOCKED_JS}}' => $wealthClarityUnlocked ? 'true' : 'false',
        '{{MEMBER_URL_LCR_GUIDE}}' => $h(memberEnvString('MEMBER_URL_LCR_GUIDE', '#')),
        '{{MEMBER_URL_LCR_PURPOSE}}' => $h(memberEnvString('MEMBER_URL_LCR_PURPOSE', '#')),
        '{{MEMBER_URL_LCR_AUDIO}}' => $h(memberEnvString('MEMBER_URL_LCR_AUDIO', '')),
        '{{MEMBER_URL_LCR_DAILY}}' => $h(memberEnvString('MEMBER_URL_LCR_DAILY', '#')),
        '{{VTURB_GUARD_VER}}' => $guardVer,
        '{{FUNNEL}}' => $funnel,
        '{{RITUAL_LOVE_WELCOME_CARD_MOD}}' => $ritualLoveUnlocked ? ' is-ritual-unlocked' : '',
        '{{MEMBER_OTO_LOVE_CHECKOUT_URL}}' => $h($ritualLoveUnlocked ? '#' : memberPortalCheckoutUrl('srp-1-l')),
        '{{MEMBER_WCR_CHECKOUT_URL}}' => $h($wealthClarityUnlocked ? '#' : memberPortalCheckoutUrl('wcr-1')),
        '{{MEMBER_URL_RITUAL_LOVE_REPORT1}}' => $h(memberEnvString('MEMBER_URL_RITUAL_LOVE_REPORT1', '#')),
        '{{MEMBER_URL_RITUAL_LOVE_REPORT2}}' => $h(memberEnvString('MEMBER_URL_RITUAL_LOVE_REPORT2', '#')),
        '{{MEMBER_URL_RITUAL_LOVE_REPORT3}}' => $h(memberEnvString('MEMBER_URL_RITUAL_LOVE_REPORT3', '#')),
        '{{MEMBER_URL_RITUAL_LOVE_WORKBOOK}}' => $h(memberEnvString('MEMBER_URL_RITUAL_LOVE_WORKBOOK', '#')),
        '{{MEMBER_URL_WCR_GUIDE}}' => $h(memberEnvString('MEMBER_URL_WCR_GUIDE', 'https://soulmirrorreading.s3.us-west-1.amazonaws.com/upsell2/wealth-clarity-ritual-guide.pdf')),
        '{{MEMBER_URL_WCR_PURPOSE}}' => $h(memberEnvString('MEMBER_URL_LCR_PURPOSE', '#')),
        '{{MEMBER_URL_WCR_AUDIO}}' => $h(memberEnvString('MEMBER_URL_LCR_AUDIO', '')),
        '{{MEMBER_URL_WCR_DAILY}}' => $h(memberEnvString('MEMBER_URL_LCR_DAILY', '#')),
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
