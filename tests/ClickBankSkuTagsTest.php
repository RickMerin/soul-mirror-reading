<?php

declare(strict_types=1);

namespace App\Tests;

use App\Domain\ClickBankSkuTags;
use PHPUnit\Framework\TestCase;

final class ClickBankSkuTagsTest extends TestCase
{
    public function testCollectsFirstMatchingKeyPerLine(): void
    {
        $names = ClickBankSkuTags::distinctSkuTagNames([
            ['itemNo' => 'smr-1'],
            ['sku' => 'OTO-X'],
        ]);
        sort($names);
        self::assertSame(['oto-x', 'smr-1'], $names);
    }

    public function testLowercasesAndDedupes(): void
    {
        $names = ClickBankSkuTags::distinctSkuTagNames([
            ['sku' => 'SMR-1'],
            ['itemNo' => 'smr-1'],
        ]);
        self::assertSame(['smr-1'], $names);
    }

    public function testAcceptsNumericItemNo(): void
    {
        $names = ClickBankSkuTags::distinctSkuTagNames([
            ['itemNo' => 1],
        ]);
        self::assertSame(['1'], $names);
    }

    public function testSkipsEmpty(): void
    {
        self::assertSame([], ClickBankSkuTags::distinctSkuTagNames([]));
        self::assertSame([], ClickBankSkuTags::distinctSkuTagNames([['other' => 'x']]));
    }
}
