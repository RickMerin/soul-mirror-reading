<?php
declare(strict_types=1);

use App\Config\AppConfig;
use App\Infrastructure\DatabaseConnection;
use App\Repository\LeadRepository;
use App\Repository\MagicLinkRepository;
use App\Repository\PurchaseRepository;
use App\Services\MemberAuthService;

$projectRoot = dirname(__DIR__, 2);
require $projectRoot . '/vendor/autoload.php';

$config = AppConfig::load($projectRoot);
$error = '';
$sent = false;

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $email = trim((string) ($_POST['email'] ?? ''));
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (!$config->hasDatabaseConfig()) {
        $error = 'Member login is not configured yet.';
    } else {
        try {
            $pdo = DatabaseConnection::fromConfig($config);
            $leads = new LeadRepository($pdo);
            $purchases = new PurchaseRepository($pdo);
            $leadId = $leads->findIdByEmail($email);

            if ($leadId === null || !$purchases->buyerHasAnyPurchase($leadId)) {
                $error = 'No buyer record found for this email yet.';
            } else {
                $auth = new MemberAuthService($config, new MagicLinkRepository($pdo));
                $magicLink = $auth->issueMagicLink($leadId);
                if ($magicLink === null) {
                    $error = 'Unable to create a secure login link right now.';
                } else {
                    $subject = 'Your Soul Mirror member login link';
                    $message = "Use this secure login link:\n\n" . $magicLink . "\n\nThis link expires soon.";
                    $headers = "From: no-reply@divinegracegift.com\r\n";
                    @mail($email, $subject, $message, $headers);
                    $sent = true;
                }
            }
        } catch (Throwable) {
            $error = 'Unable to process login right now.';
        }
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
  <p>Enter the email you used to buy. We will send a secure one-time login link.</p>

  <form method="post" action="">
    <label for="email">Buyer Email</label>
    <input id="email" name="email" type="email" required autocomplete="email">
    <button type="submit">Send Secure Login Link</button>
  </form>

  <?php if ($error !== ''): ?>
    <p class="error"><?= htmlspecialchars($error, ENT_QUOTES) ?></p>
  <?php endif; ?>
  <?php if ($sent): ?>
    <p class="ok">If this email has a buyer record, your login link is on its way.</p>
  <?php endif; ?>
</body>
</html>
