<?php

declare(strict_types=1);

namespace App\Tests;

use App\Domain\ReadingTemplatePersonalizer;
use App\Domain\SoulMirrorCardImageUrlBuilder;
use App\Services\LocalTarotPredictionRepository;
use PHPUnit\Framework\TestCase;

final class ReadingTemplatePersonalizerTest extends TestCase
{
    public function testPersonalizeReplacesNameCardsAndImages(): void
    {
        $template = '<div class="devbar">x</div><span>Sarah</span>'
            . '<img src="https://soulmirrorreading.com/cards/78-king-of-pentacles.png">'
            . '<p>King of Pentacles</p><p>The Lovers</p><p>The Hermit</p>'
            . '<h3 class="bodyhead">The King of Pentacles in Your Wealth Gate</h3>'
            . '<div class="kicker">Your Mirror Block · Type One</div><p>your type is visible</p>';

        $personalizer = new ReadingTemplatePersonalizer(new SoulMirrorCardImageUrlBuilder());
        $html = $personalizer->personalize($template, [
            'firstName' => 'Alex',
            'gender' => 'Female',
            'wealthCardName' => 'Ace of Cups',
            'loveCardName' => 'The Star',
            'lifeCardName' => 'Judgement',
            'wealthCardId' => 37,
            'loveCardId' => 18,
            'lifeCardId' => 21,
            'mirrorBlockSlug' => 'not-yet-ready',
        ]);

        self::assertStringNotContainsString('Sarah', $html);
        self::assertStringContainsString('Alex', $html);
        self::assertStringContainsString('Ace of Cups', $html);
        self::assertStringContainsString('The Star', $html);
        self::assertStringContainsString('Judgement', $html);
        self::assertStringContainsString('cards/37-ace-of-cups.png', $html);
        self::assertStringNotContainsString('king-of-pentacles', $html);
        self::assertStringNotContainsString('devbar', $html);
    }

    public function testPersonalizeInjectsLocalTarotReadings(): void
    {
        $fixture = dirname(__DIR__) . '/data/tarot-predictions.json';
        $template = '<h3 class="bodyhead">The King of Pentacles in Your Wealth Gate</h3>'
            . '<h3 class="bodyhead">The Lovers in Your Love Thread</h3>'
            . '<h3 class="bodyhead">The Hermit in Your Destiny Current</h3>';

        $personalizer = new ReadingTemplatePersonalizer(
            new SoulMirrorCardImageUrlBuilder(),
            new LocalTarotPredictionRepository($fixture),
        );

        $html = $personalizer->personalize($template, [
            'firstName' => 'Alex',
            'gender' => 'Female',
            'wealthCardName' => 'King of Pentacles',
            'loveCardName' => 'The Lovers',
            'lifeCardName' => 'The Hermit',
            'wealthCardId' => 78,
            'loveCardId' => 7,
            'lifeCardId' => 10,
            'mirrorBlockSlug' => 'not-yet-ready',
        ]);

        self::assertStringContainsString('card-json-reading', $html);
        self::assertStringContainsString('The single and eligible have a good chance', $html);
        self::assertStringContainsString('You shall be successful in bringing stability', $html);
        self::assertStringContainsString('You will get an opportunity to move away from routine work', $html);
    }

    public function testPersonalizeUpdatesMirrorBlockHeadingForTypeTwo(): void
    {
        $template = '<div class="reveal"><div class="rl">Your Mirror Block</div>'
            . '<h3>The Not Yet Ready Block</h3></div>'
            . '<div class="kicker">Your Mirror Block · Type One</div>'
            . '<div class="kicker">Type Two</div><p>they get close</p>'
            . '<div class="kicker">Type Three</div><p>they perform</p>'
            . '<div class="kicker">Type Four</div><p>they delegate</p>'
            . '<div class="part">Part Four</div>';

        $personalizer = new ReadingTemplatePersonalizer(new SoulMirrorCardImageUrlBuilder());
        $html = $personalizer->personalize($template, [
            'firstName' => 'Alex',
            'gender' => 'Female',
            'wealthCardName' => 'King of Pentacles',
            'loveCardName' => 'The Lovers',
            'lifeCardName' => 'The Hermit',
            'wealthCardId' => 78,
            'loveCardId' => 7,
            'lifeCardId' => 10,
            'mirrorBlockSlug' => 'waiting-to-end',
        ]);

        self::assertStringContainsString(
            '<h3>Waiting for the Good Thing to End</h3>',
            $html,
        );
        self::assertStringContainsString('Your Mirror Block · Type Two', $html);
    }
}
