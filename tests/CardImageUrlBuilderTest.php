<?php

declare(strict_types=1);

namespace App\Tests;

use App\Domain\CardImageUrlBuilder;
use PHPUnit\Framework\TestCase;

final class CardImageUrlBuilderTest extends TestCase
{
    public function testFirstCard(): void
    {
        $b = new CardImageUrlBuilder();
        $this->assertSame(
            'https://www.trustedtarot.com/img/cards/the-fool.png',
            $b->buildUrl(1)
        );
    }

    public function testLastCard(): void
    {
        $b = new CardImageUrlBuilder();
        $this->assertSame(
            'https://www.trustedtarot.com/img/cards/king-of-pentacles.png',
            $b->buildUrl(78)
        );
    }

    public function testInvalidIdReturnsEmptyString(): void
    {
        $b = new CardImageUrlBuilder();
        $this->assertSame('', $b->buildUrl(0));
        $this->assertSame('', $b->buildUrl(99));
    }
}
