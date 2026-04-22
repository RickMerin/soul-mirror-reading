<?php
declare(strict_types=1);

use App\Config\AppConfig;
use App\Infrastructure\DatabaseConnection;
use App\Repository\LeadRepository;
use App\Repository\PurchaseRepository;
use App\Services\MemberAutoLoginService;
use App\Services\MemberUrlBuilder;

$projectRoot = dirname(__DIR__, 2);
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
    session_set_cookie_params([
        'httponly' => true,
        'secure' => !in_array($_SERVER['HTTP_HOST'] ?? '', ['localhost', '127.0.0.1'], true),
        'samesite' => 'Lax',
    ]);
    session_start();
    session_regenerate_id(true);
    $_SESSION['member_lead_id'] = $leadId;
    header('Location: ' . MemberUrlBuilder::indexPath());
    exit;
}

$config = AppConfig::load($projectRoot);
$error = 'Access denied. Please use your purchase link to enter the member portal.';
$cemail = trim((string) ($_GET['cemail'] ?? ''));
$postedEmail = trim((string) ($_POST['email'] ?? ''));
$requestMethod = (string) ($_SERVER['REQUEST_METHOD'] ?? 'GET');

if (!$config->hasDatabaseConfig()) {
    $error = 'Member login is not configured yet.';
} elseif ($cemail !== '' || ($requestMethod === 'POST' && $postedEmail !== '')) {
    $email = $cemail !== '' ? $cemail : $postedEmail;
    try {
        $leadId = resolveAuthorizedLeadId($config, $email);
        if ($leadId !== null) {
            loginMember($leadId);
        }
        $error = 'Access denied. No qualifying purchase was found for that email.';
    } catch (Throwable) {
        $error = 'Unable to process login right now.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Member Login - Soul Mirror</title>
  <style>
    body { font-family: Arial, sans-serif; max-width: 560px; margin: 48px auto; padding: 0 16px; color: #261544; }
    h1 { margin-bottom: 8px; }
    p { line-height: 1.5; }
    label { display: block; margin: 20px 0 8px; }
    input { width: 100%; padding: 10px; font-size: 16px; }
    button { margin-top: 16px; background: #4a2f8f; color: #fff; border: none; padding: 12px 16px; cursor: pointer; }
    .error { color: #a12929; margin-top: 12px; }
    .ok { color: #15633a; margin-top: 12px; }
  </style>
</head>
<body>
  <h1>Member Portal Login</h1>
  <p>Use your ClickBank purchase link, or log in manually with your buyer email.</p>
  <form method="post" action="">
    <label for="email">Buyer Email</label>
    <input id="email" name="email" type="email" required autocomplete="email" value="<?= htmlspecialchars($postedEmail, ENT_QUOTES) ?>">
    <button type="submit">Log In</button>
  </form>
  <p class="error"><?= htmlspecialchars($error, ENT_QUOTES) ?></p>
</body>
</html>
