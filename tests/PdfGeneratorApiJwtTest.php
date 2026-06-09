<?php

declare(strict_types=1);

namespace App\Tests;

use App\Domain\PdfGeneratorApiJwt;
use PHPUnit\Framework\TestCase;

final class PdfGeneratorApiJwtTest extends TestCase
{
    public function testCreateTokenHasThreeSegments(): void
    {
        $jwt = new PdfGeneratorApiJwt();
        $token = $jwt->createToken('api-key', 'api-secret', 'user@example.com', 120);

        self::assertSame(3, count(explode('.', $token)));
    }
}
