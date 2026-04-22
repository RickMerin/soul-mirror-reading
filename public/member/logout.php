<?php
declare(strict_types=1);

use App\Services\MemberUrlBuilder;

$projectRoot = dirname(__DIR__, 2);
require $projectRoot . '/vendor/autoload.php';

session_start();
$_SESSION = [];
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 3600, $params['path'], $params['domain'], (bool) $params['secure'], (bool) $params['httponly']);
}
session_destroy();

header('Location: ' . MemberUrlBuilder::loginPath());
exit;
