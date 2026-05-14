<?php

declare(strict_types=1);

namespace App\Tests;

use App\Domain\SalesPageQueryBuilder;
use PHPUnit\Framework\TestCase;

final class SalesPageQueryBuilderTest extends TestCase
{
    public function testBuildsPersonalizationQuery(): void
    {
        $builder = new SalesPageQueryBuilder();
        $query = $builder->buildQueryString([
            'first_name' => 'Sarah',
            'love_card' => 'The Lovers',
            'love_card_image' => 'https://www.trustedtarot.com/img/cards/the-lovers.png',
            'life_card' => 'The Hermit',
            'life_card_image' => 'https://www.trustedtarot.com/img/cards/the-hermit.png',
            'wealth_card' => 'King of Pentacles',
            'wealth_card_image' => 'https://www.trustedtarot.com/img/cards/king-of-pentacles.png',
        ]);

        parse_str(ltrim($query, '?'), $parsed);
        $this->assertSame('Sarah', $parsed['first_name'] ?? null);
        $this->assertSame('The Lovers', $parsed['love_card'] ?? null);
        $this->assertSame(
            'https://www.trustedtarot.com/img/cards/the-lovers.png',
            $parsed['love_card_image'] ?? null,
        );
    }

    public function testOmitsEmptyValues(): void
    {
        $builder = new SalesPageQueryBuilder();
        $this->assertSame('', $builder->buildQueryString([]));
        $this->assertSame('', $builder->buildQueryString([
            'first_name' => '   ',
            'love_card' => '',
        ]));
    }
}
