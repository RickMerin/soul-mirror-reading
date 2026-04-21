<?php
declare(strict_types=1);

use App\Config\AppConfig;
use App\Infrastructure\DatabaseConnection;
use App\Repository\MagicLinkRepository;
use App\Services\MemberAuthService;

$projectRoot = dirname(__DIR__, 2);
require $projectRoot . '/vendor/autoload.php';

$config = AppConfig::load($projectRoot);
$token = trim((string) ($_GET['token'] ?? ''));

if ($token === '' || !$config->hasDatabaseConfig()) {
    http_response_code(400);
    echo 'Invalid login link.';
    exit;
}

try {
    $pdo = DatabaseConnection::fromConfig($config);
    $auth = new MemberAuthService($config, new MagicLinkRepository($pdo));
    $leadId = $auth->verifyMagicToken($token);
    if ($leadId === null) {
        http_response_code(401);
        echo 'This login link is invalid or expired.';
        exit;
    }

    session_set_cookie_params([
        'httponly' => true,
        'secure' => !in_array($_SERVER['HTTP_HOST'] ?? '', ['localhost', '127.0.0.1'], true),
        'samesite' => 'Lax',
    ]);
    session_start();
    session_regenerate_id(true);
    $_SESSION['member_lead_id'] = $leadId;
    header('Location: /member/index.php');
    exit;
} catch (Throwable) {
    http_response_code(500);
    echo 'Unable to verify login link.';
}
