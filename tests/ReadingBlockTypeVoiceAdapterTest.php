<?php

declare(strict_types=1);

namespace App\Tests;

use App\Domain\ReadingBlockTypeVoiceAdapter;
use PHPUnit\Framework\TestCase;

final class ReadingBlockTypeVoiceAdapterTest extends TestCase
{
    public function testTypeOneBuyerKeepsPrimaryKicker(): void
    {
        $html = $this->sampleHtml();
        $adapted = (new ReadingBlockTypeVoiceAdapter())->adapt($html, 'not-yet-ready');

        self::assertStringContainsString('Your Mirror Block · Type One', $adapted);
        self::assertStringContainsString('your type is visible', $adapted);
        self::assertStringNotContainsString('Your Mirror Block · Type Two', $adapted);
    }

    public function testTypeTwoBuyerPromotesFrameworkSection(): void
    {
        $html = $this->sampleHtml();
        $adapted = (new ReadingBlockTypeVoiceAdapter())->adapt($html, 'waiting-to-end');

        self::assertStringContainsString('Your Mirror Block · Type Two', $adapted);
        self::assertStringContainsString('<div class="kicker">Type One</div>', $adapted);
        self::assertStringContainsString('you get close', $adapted);
        self::assertStringContainsString('the second one.', $adapted);
    }

    private function sampleHtml(): string
    {
        return '<div class="blurb">Yours is the first, named precisely and addressed to you.</div>'
            . '<p>Your cards named yours, Sarah: the first one.</p>'
            . '<div class="kicker">Your Mirror Block · Type One</div><p>your type is visible</p>'
            . '<div style="font-family:Arial,sans-serif;">Not your type · included for the full framework</div>'
            . '<div class="kicker">Type Two</div><p>they get close to what they want</p>'
            . '<div class="kicker">Type Three</div><p>they perform a smaller version</p>'
            . '<div class="kicker">Type Four</div><p>they do not delegate well</p>'
            . '<div class="part">Part Four</div>';
    }
}
