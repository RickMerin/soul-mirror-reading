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

    /**
     * Live-chat funnel front-ends sell the same $37 reading under separate SKUs; they must deliver.
     */
    public function testPurchaseIncludesMainReadingMatchesLiveChatFrontEnds(): void
    {
        self::assertTrue(ReadingProductSkus::purchaseIncludesMainReading([
            ['sku' => 'smr-1-w-lc'],
        ]));
        self::assertTrue(ReadingProductSkus::purchaseIncludesMainReading([
            ['sku' => 'smr-1-l-lc'],
        ]));
    }
}
