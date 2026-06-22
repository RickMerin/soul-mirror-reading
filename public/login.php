<?php
declare(strict_types=1);

use App\Config\AppConfig;
use App\Infrastructure\DatabaseConnection;
use App\Repository\LeadRepository;
use App\Repository\PurchaseRepository;
use App\Services\MemberAutoLoginService;
use App\Domain\LoginAutoSessionGuard;
use App\Services\MemberUrlBuilder;

$projectRoot = dirname(__DIR__);
require $projectRoot . '/vendor/autoload.php';

/**
 * @return int|null
 */
function resolveAuthorizedLeadId(AppConfig $config, string $email): ?int
{
    if (!$config->hasDatabaseConfig()) {
        return null;
    }

    $pdo = DatabaseConnection::fromConfig($config);
    $service = new MemberAutoLoginService(
        new LeadRepository($pdo),
        new PurchaseRepository($pdo),
    );

    return $service->resolveAuthorizedLeadId($email);
}

function loginMember(int $leadId): void
{
    startMemberSession();
    session_regenerate_id(true);
    $_SESSION['member_lead_id'] = $leadId;
    header('Location: ' . MemberUrlBuilder::indexPath());
    exit;
}

function startMemberSession(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    session_set_cookie_params([
        'httponly' => true,
        'secure' => !in_array($_SERVER['HTTP_HOST'] ?? '', ['localhost', '127.0.0.1'], true),
        'samesite' => 'Lax',
    ]);
    session_start();
}

$config = AppConfig::load($projectRoot);
$statusMessage = 'Use your ClickBank purchase link, or log in manually with your buyer email.';
$statusClass = 'notice';
$cemail = trim((string) ($_GET['cemail'] ?? ''));
$postedEmail = trim((string) ($_POST['email'] ?? ''));
$requestMethod = (string) ($_SERVER['REQUEST_METHOD'] ?? 'GET');

if (!$config->hasDatabaseConfig()) {
    $statusMessage = 'Member login is not configured yet.';
    $statusClass = 'error';
} elseif ($cemail !== '' || ($requestMethod === 'POST' && $postedEmail !== '')) {
    startMemberSession();
    $hasMemberSession = isset($_SESSION['member_lead_id']) && is_int($_SESSION['member_lead_id']);
    if (LoginAutoSessionGuard::shouldBypassCemailAutoLogin($requestMethod, $cemail !== '', $hasMemberSession)) {
        header('Location: ' . MemberUrlBuilder::indexPath());
        exit;
    }

    $email = $cemail !== '' ? $cemail : $postedEmail;
    try {
        $leadId = resolveAuthorizedLeadId($config, $email);
        if ($leadId !== null) {
            loginMember($leadId);
        }
        $statusMessage = 'Access denied. No qualifying purchase was found for that email.';
        $statusClass = 'error';
    } catch (Throwable) {
        $statusMessage = 'Unable to process login right now.';
        $statusClass = 'error';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Member Login - Soul Mirror</title>
  <link rel="icon" type="image/svg+xml" href="/favicon.svg">
  <style>
    :root {
      --bg-dark: #130a26;
      --bg-mid: #241144;
      --card-bg: rgba(255, 255, 255, 0.95);
      --text: #20113f;
      --text-soft: #5b4a82;
      --brand: #4a2f8f;
      --brand-hover: #3c2473;
      --accent: #d4af37;
      --border: rgba(74, 47, 143, 0.2);
      --error-bg: #fee8e8;
      --error-text: #7f1f1f;
      --notice-bg: #f3ecff;
      --notice-text: #3e2a77;
    }

    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      min-height: 100vh;
      font-family: "Inter", "Segoe UI", Arial, sans-serif;
      color: var(--text);
      background:
        radial-gradient(circle at 20% 15%, rgba(122, 88, 200, 0.28), transparent 40%),
        radial-gradient(circle at 85% 85%, rgba(212, 175, 55, 0.16), transparent 35%),
        linear-gradient(150deg, var(--bg-dark), var(--bg-mid));
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 24px;
    }

    .login-shell {
      width: 100%;
      max-width: 520px;
    }

    body > footer {
      width: 100%;
      max-width: 520px;
    }

    .login-card {
      background: var(--card-bg);
      border: 1px solid var(--border);
      border-radius: 18px;
      padding: 32px 28px;
      box-shadow: 0 18px 45px rgba(18, 9, 37, 0.35);
    }

    .eyebrow {
      margin: 0 0 10px;
      font-size: 12px;
      font-weight: 600;
      letter-spacing: 0.08em;
      text-transform: uppercase;
      color: var(--text-soft);
    }

    h1 {
      margin: 0 0 10px;
      font-family: Georgia, "Times New Roman", serif;
      font-size: clamp(28px, 4vw, 34px);
      line-height: 1.15;
      color: #1d0f38;
    }

    .intro {
      margin: 0 0 22px;
      color: var(--text-soft);
      line-height: 1.55;
      font-size: 15px;
    }

    label {
      display: block;
      margin: 0 0 8px;
      font-weight: 600;
      color: #2d1760;
      font-size: 14px;
    }

    input[type="email"] {
      width: 100%;
      border: 1px solid var(--border);
      border-radius: 10px;
      padding: 12px 13px;
      font-size: 16px;
      color: var(--text);
      background: #fff;
      transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    input[type="email"]:focus {
      outline: none;
      border-color: var(--brand);
      box-shadow: 0 0 0 3px rgba(74, 47, 143, 0.16);
    }

    .submit-btn {
      width: 100%;
      margin-top: 14px;
      border: 0;
      border-radius: 10px;
      padding: 13px 16px;
      font-size: 16px;
      font-weight: 700;
      color: #fff;
      background: var(--brand);
      cursor: pointer;
      transition: background 0.2s ease, transform 0.2s ease;
    }

    .submit-btn:hover,
    .submit-btn:focus-visible {
      background: var(--brand-hover);
    }

    .submit-btn:focus-visible {
      outline: 2px solid var(--accent);
      outline-offset: 2px;
    }

    .status {
      margin-top: 16px;
      border-radius: 10px;
      padding: 10px 12px;
      font-size: 14px;
      line-height: 1.45;
      border: 1px solid transparent;
    }

    .status.notice {
      background: var(--notice-bg);
      color: var(--notice-text);
      border-color: rgba(74, 47, 143, 0.2);
    }

    .status.error {
      background: var(--error-bg);
      color: var(--error-text);
      border-color: rgba(161, 41, 41, 0.35);
    }

    .support {
      margin-top: 16px;
      text-align: center;
      font-size: 13px;
      color: var(--text-soft);
    }

    .support a {
      color: #352067;
      font-weight: 600;
    }

    .shield {
      display: block;
      margin: 16px auto 0;
      text-align: center;
      font-size: 12px;
      color: #43306c;
    }

    @media (max-width: 540px) {
      body {
        padding: 16px;
      }

      .login-card {
        padding: 24px 18px;
        border-radius: 14px;
      }
    }
  </style>
</head>
<body>
  <main class="login-shell">
    <section class="login-card" aria-labelledby="member-login-title">
      <p class="eyebrow">Soul Mirror Member Area</p>
      <h1 id="member-login-title">Member Portal Login</h1>
      <p class="intro">Enter the email used for your purchase to continue into your member dashboard.</p>
      <form method="post" action="">
        <label for="email">Buyer Email</label>
        <input id="email" name="email" type="email" required autocomplete="email" value="<?= htmlspecialchars($postedEmail, ENT_QUOTES) ?>">
        <button class="submit-btn" type="submit">Log In</button>
      </form>
      <?php if ($statusMessage !== ''): ?>
        <p class="status <?= htmlspecialchars($statusClass, ENT_QUOTES) ?>" role="status" aria-live="polite"><?= htmlspecialchars($statusMessage, ENT_QUOTES) ?></p>
      <?php endif; ?>
      <p class="shield">Secure member access powered by your purchase record.</p>
      <p class="support">Need help with access? <a href="mailto:support@soulmirrorreading.com">Contact support</a>.</p>
    </section>
  </main>
  <footer style="text-align:center;padding:30px 20px;font-family:system-ui,-apple-system,sans-serif;font-size:13px;line-height:1.7;color:#9a93b3;border-top:1px solid rgba(212,175,55,.18);margin-top:34px">
    <p style="margin:0 0 6px">
      <a href="/privacy-policy" style="color:#b7afd0;text-decoration:underline;text-underline-offset:2px">Privacy Policy</a> &nbsp;&middot;&nbsp;
      <a href="/terms-conditions" style="color:#b7afd0;text-decoration:underline;text-underline-offset:2px">Terms &amp; Conditions</a> &nbsp;&middot;&nbsp;
      <a href="/refund-return-policy" style="color:#b7afd0;text-decoration:underline;text-underline-offset:2px">Refund &amp; Return Policy</a> &nbsp;&middot;&nbsp;
      <a href="mailto:support@soulmirrorreading.com" style="color:#b7afd0;text-decoration:underline;text-underline-offset:2px">Contact Us</a>
    </p>
    <p style="margin:0">Copyright &copy; 2026 Soul Mirror Reading. All Rights Reserved.</p>
  </footer>
</body>
</html>
