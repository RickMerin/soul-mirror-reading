<?php
declare(strict_types=1);

use App\Services\MemberUrlBuilder;

$projectRoot = dirname(__DIR__, 2);
require $projectRoot . '/vendor/autoload.php';

header('Location: ' . MemberUrlBuilder::logoutPath(), true, 301);
exit;
