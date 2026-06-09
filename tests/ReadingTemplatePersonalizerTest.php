<?php

declare(strict_types=1);

namespace App\Tests;

use App\Domain\ReadingTemplatePersonalizer;
use App\Domain\SoulMirrorCardImageUrlBuilder;
use PHPUnit\Framework\TestCase;

final class ReadingTemplatePersonalizerTest extends TestCase
{
    public function testPersonalizeReplacesNameAndCards(): void
    {
        $template = '<div class="devbar">x</div><span>Sarah</span>'
            . '<p>King of Pentacles</p><p>The Lovers</p><p>The Hermit</p>'
            . '<div class="kicker">Type One</div><p>your type is visible</p>';

        $personalizer = new ReadingTemplatePersonalizer(new SoulMirrorCardImageUrlBuilder());
        $html = $personalizer->personalize($template, [
            'firstName' => 'Alex',
            'gender' => 'Female',
            'wealthCardName' => 'Ace of Cups',
            'loveCardName' => 'The Star',
            'lifeCardName' => 'Judgement',
            'wealthCardId' => 23,
            'loveCardId' => 18,
            'lifeCardId' => 21,
            'mirrorBlockSlug' => 'not-yet-ready',
        ]);

        self::assertStringNotContainsString('Sarah', $html);
        self::assertStringContainsString('Alex', $html);
        self::assertStringContainsString('Ace of Cups', $html);
        self::assertStringContainsString('The Star', $html);
        self::assertStringContainsString('Judgement', $html);
        self::assertStringNotContainsString('devbar', $html);
    }
}
