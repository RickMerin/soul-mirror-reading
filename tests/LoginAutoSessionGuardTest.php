<?php

declare(strict_types=1);

namespace App\Tests;

use App\Domain\LoginAutoSessionGuard;
use PHPUnit\Framework\TestCase;

final class LoginAutoSessionGuardTest extends TestCase
{
    public function testBypassesCemailAutoLoginWhenSessionAlreadyExistsOnGet(): void
    {
        self::assertTrue(LoginAutoSessionGuard::shouldBypassCemailAutoLogin('GET', true, true));
    }

    public function testDoesNotBypassWhenRequestIsPost(): void
    {
        self::assertFalse(LoginAutoSessionGuard::shouldBypassCemailAutoLogin('POST', true, true));
    }

    public function testDoesNotBypassWithoutCemailOrSession(): void
    {
        self::assertFalse(LoginAutoSessionGuard::shouldBypassCemailAutoLogin('GET', false, true));
        self::assertFalse(LoginAutoSessionGuard::shouldBypassCemailAutoLogin('GET', true, false));
    }
}
