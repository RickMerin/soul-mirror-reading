<?php

declare(strict_types=1);

namespace App\Tests;

use App\Domain\MirrorBlockCatalog;
use App\Domain\MirrorBlockResolver;
use PHPUnit\Framework\TestCase;

final class MirrorBlockResolverTest extends TestCase
{
    public function testResolveFromCardIdsReturnsCanonicalSlug(): void
    {
        $resolver = new MirrorBlockResolver();
        $slug = $resolver->resolveFromCardIds([14, 6, 51]);

        self::assertContains($slug, MirrorBlockCatalog::allSlugs());
    }

    public function testResolveFromCardIdsIsOrderIndependent(): void
    {
        $resolver = new MirrorBlockResolver();
        $a = $resolver->resolveFromCardIds([14, 6, 51]);
        $b = $resolver->resolveFromCardIds([51, 14, 6]);

        self::assertSame($a, $b);
    }

    public function testResolveFromCardIdsDefaultsWhenInvalid(): void
    {
        $resolver = new MirrorBlockResolver();

        self::assertSame('not-yet-ready', $resolver->resolveFromCardIds([1, 2]));
    }
}
