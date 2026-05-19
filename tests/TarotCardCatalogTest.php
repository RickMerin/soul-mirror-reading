<?php

declare(strict_types=1);

namespace App\Tests;

use App\Domain\TarotCardCatalog;
use PHPUnit\Framework\TestCase;

final class TarotCardCatalogTest extends TestCase
{
    public function testHasSeventyEightCards(): void
    {
        $this->assertCount(78, TarotCardCatalog::all());
    }

    public function testFoolFrontendMapsToApiFool(): void
    {
        $this->assertSame(57, TarotCardCatalog::frontendIdToApiId(1));
        $this->assertSame(1, TarotCardCatalog::apiIdToFrontendId(57));
    }

    public function testKingOfWandsFrontendMapsToApiOne(): void
    {
        $this->assertSame(36, TarotCardCatalog::byApiId(1)?->frontendId);
        $this->assertSame(1, TarotCardCatalog::byFrontendId(36)?->apiId);
    }

    public function testRoundTripAllFrontendIds(): void
    {
        foreach (TarotCardCatalog::all() as $card) {
            $api = TarotCardCatalog::frontendIdToApiId($card->frontendId);
            $this->assertSame($card->apiId, $api);
            $this->assertSame($card->frontendId, TarotCardCatalog::apiIdToFrontendId($api));
        }
    }
}
