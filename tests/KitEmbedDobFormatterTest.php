<?php

declare(strict_types=1);

namespace App\Tests;

use App\Domain\KitEmbedDobFormatter;
use PHPUnit\Framework\TestCase;

final class KitEmbedDobFormatterTest extends TestCase
{
    public function testApiToKitEmbedFormatsDob(): void
    {
        $this->assertSame('25-Mar-1990', KitEmbedDobFormatter::apiToKitEmbed('03/25/1990'));
        $this->assertSame('23-Oct-1970', KitEmbedDobFormatter::apiToKitEmbed('10/23/1970'));
    }

    public function testKitEmbedToApiFormatsDob(): void
    {
        $this->assertSame('03/25/1990', KitEmbedDobFormatter::kitEmbedToApi('25-Mar-1990'));
        $this->assertSame('10/23/1970', KitEmbedDobFormatter::kitEmbedToApi('23-Oct-1970'));
    }

    public function testKitEmbedToApiRejectsInvalidDob(): void
    {
        $this->assertNull(KitEmbedDobFormatter::kitEmbedToApi('not-a-date'));
        $this->assertNull(KitEmbedDobFormatter::kitEmbedToApi('31-Feb-1990'));
    }
}
