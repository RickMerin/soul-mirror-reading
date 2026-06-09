<?php

declare(strict_types=1);

namespace App\Tests;

use App\Domain\ReadingProductSkus;
use PHPUnit\Framework\TestCase;

final class ReadingProductSkusTest extends TestCase
{
    public function testPurchaseIncludesMainReadingMatchesSmrSku(): void
    {
        self::assertTrue(ReadingProductSkus::purchaseIncludesMainReading([
            ['sku' => 'smr-1-w'],
        ]));
    }

    public function testPurchaseIncludesMainReadingIgnoresRitualSku(): void
    {
        self::assertFalse(ReadingProductSkus::purchaseIncludesMainReading([
            ['sku' => 'srp-1'],
        ]));
    }
}
