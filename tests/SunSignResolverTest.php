<?php

declare(strict_types=1);

namespace App\Tests;

use App\Domain\SunSignResolver;
use PHPUnit\Framework\TestCase;

final class SunSignResolverTest extends TestCase
{
    public function testFromDobStringAries(): void
    {
        $r = new SunSignResolver();
        $this->assertSame('aries', $r->fromDobString('03/25/1990'));
    }

    public function testFromDobStringCapricornYearBoundary(): void
    {
        $r = new SunSignResolver();
        $this->assertSame('capricorn', $r->fromDobString('12/25/2000'));
    }

    public function testFromDobStringReturnsNullForEmpty(): void
    {
        $r = new SunSignResolver();
        $this->assertNull($r->fromDobString(''));
        $this->assertNull($r->fromDobString(null));
    }

    public function testFromMonthDayPisces(): void
    {
        $r = new SunSignResolver();
        $this->assertSame('pisces', $r->fromMonthDay(3, 20));
    }
}
