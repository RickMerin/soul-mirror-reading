<?php

declare(strict_types=1);

namespace App\Domain;

final class LoginAutoSessionGuard
{
    public static function shouldBypassCemailAutoLogin(
        string $requestMethod,
        bool $hasCemail,
        bool $hasMemberSession,
    ): bool {
        if (!$hasCemail || !$hasMemberSession) {
            return false;
        }

        return strtoupper(trim($requestMethod)) !== 'POST';
    }
}
