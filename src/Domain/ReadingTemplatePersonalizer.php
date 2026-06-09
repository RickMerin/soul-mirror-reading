<?php

declare(strict_types=1);

namespace App\Domain;

use App\Services\LocalTarotPredictionRepository;

/**
 * Fills the Soul Mirror Reading HTML template with buyer-specific data.
 */
final class ReadingTemplatePersonalizer
{
    /** @var array<int, non-empty-string> */
    private const BLOCK_HEADING = [
        1 => 'The Not Yet Ready Block',
        2 => 'Waiting for the Good Thing to End',
        3 => 'Too Much / Making Yourself Smaller',
        4 => 'Cannot Let Others Help',
    ];

    public function __construct(
        private readonly SoulMirrorCardImageUrlBuilder $cardImages,
        private readonly ?LocalTarotPredictionRepository $tarotReadings = null,
        private readonly ReadingBlockTypeVoiceAdapter $blockVoice = new ReadingBlockTypeVoiceAdapter(),
    ) {}

    /**
     * @param array{
     *   firstName: string,
     *   gender: string,
     *   wealthCardName: string,
     *   loveCardName: string,
     *   lifeCardName: string,
     *   wealthCardId?: int|string|null,
     *   loveCardId?: int|string|null,
     *   lifeCardId?: int|string|null,
     *   mirrorBlockSlug: string,
     * } $buyer
     */
    public function personalize(string $templateHtml, array $buyer): string
    {
        $type = MirrorBlockCatalog::bySlug($buyer['mirrorBlockSlug']) ?? MirrorBlockCatalog::bySlug('not-yet-ready');
        if ($type === null) {
            throw new \InvalidArgumentException('Unknown mirror block slug.');
        }

        $html = $templateHtml;
        $html = $this->blockVoice->adapt($html, $type['slug']);
        $html = $this->applyBuyerMirrorBlockHeadings($html, $type['typeNumber']);

        $sampleType = MirrorBlockCatalog::bySlug('not-yet-ready');
        $sampleCards = [
            $sampleType['sampleWealthCard'] ?? 'King of Pentacles',
            $sampleType['sampleLoveCard'] ?? 'The Lovers',
            $sampleType['sampleLifeCard'] ?? 'The Hermit',
        ];
        $buyerCards = [
            $buyer['wealthCardName'],
            $buyer['loveCardName'],
            $buyer['lifeCardName'],
        ];

        foreach ($sampleCards as $i => $sampleName) {
            $html = str_replace($sampleName, $buyerCards[$i], $html);
        }

        $html = str_replace('Sarah', $buyer['firstName'], $html);
        $html = str_replace('{{first_name}}', $buyer['firstName'], $html);
        $html = str_replace('{{wealth_card}}', $buyer['wealthCardName'], $html);
        $html = str_replace('{{love_card}}', $buyer['loveCardName'], $html);
        $html = str_replace('{{life_card}}', $buyer['lifeCardName'], $html);
        $html = str_replace('{{block_type}}', $type['name'], $html);

        $html = $this->injectCardImages(
            $html,
            $sampleCards,
            $buyer['wealthCardName'],
            $buyer['loveCardName'],
            $buyer['lifeCardName'],
            $buyer['wealthCardId'] ?? null,
            $buyer['loveCardId'] ?? null,
            $buyer['lifeCardId'] ?? null,
        );

        $html = $this->injectTarotReadings(
            $html,
            $this->resolveCardId($buyer['loveCardId'] ?? null),
            $this->resolveCardId($buyer['lifeCardId'] ?? null),
            $this->resolveCardId($buyer['wealthCardId'] ?? null),
        );

        if (strcasecmp($buyer['gender'], 'Male') === 0) {
            $html = strtr($html, [
                ' she ' => ' he ',
                ' She ' => ' He ',
                ' her ' => ' him ',
                ' Her ' => ' Him ',
            ]);
        }

        $html = $this->stripDevBar($html);

        return $html;
    }

    private function applyBuyerMirrorBlockHeadings(string $html, int $typeNumber): string
    {
        $heading = self::BLOCK_HEADING[$typeNumber] ?? self::BLOCK_HEADING[1];
        $typeOneHeading = self::BLOCK_HEADING[1];

        if ($typeNumber === 1) {
            return $html;
        }

        $html = str_replace(
            '<div class="rl">Your Mirror Block</div><h3>' . $typeOneHeading . '</h3>',
            '<div class="rl">Your Mirror Block</div><h3>' . $heading . '</h3>',
            $html,
        );

        $html = str_replace(
            'The Not Yet Ready Block has survived this long',
            $heading . ' has survived this long',
            $html,
        );

        return $html;
    }

    /**
     * @param list<non-empty-string> $sampleCardNames
     */
    private function injectCardImages(
        string $html,
        array $sampleCardNames,
        string $wealthName,
        string $loveName,
        string $lifeName,
        int|string|null $wealthId,
        int|string|null $loveId,
        int|string|null $lifeId,
    ): string {
        $buyerNames = [$wealthName, $loveName, $lifeName];
        $buyerIds = [$wealthId, $loveId, $lifeId];

        foreach ($sampleCardNames as $i => $sampleName) {
            $sampleUrl = $this->cardImages->buildUrlForCardName($sampleName);
            $buyerUrl = $this->resolveCardUrl($buyerIds[$i] ?? null, $buyerNames[$i]);
            if ($sampleUrl === '' || $buyerUrl === '' || $sampleUrl === $buyerUrl) {
                continue;
            }
            $html = str_replace($sampleUrl, $buyerUrl, $html);
        }

        return $html;
    }

    private function injectTarotReadings(string $html, int $loveId, int $lifeId, int $wealthId): string
    {
        if ($this->tarotReadings === null || $loveId < 1 || $lifeId < 1 || $wealthId < 1) {
            return str_replace(
                ['{{wealth_reading}}', '{{love_reading}}', '{{life_reading}}'],
                ['', '', ''],
                $html,
            );
        }

        $reading = $this->tarotReadings->predict($loveId, $lifeId, $wealthId);
        $slots = [
            '{{wealth_reading}}' => $this->formatReadingHtml($reading->finance),
            '{{love_reading}}' => $this->formatReadingHtml($reading->love),
            '{{life_reading}}' => $this->formatReadingHtml($reading->career),
        ];

        $html = str_replace(array_keys($slots), array_values($slots), $html);

        $insertions = [
            'in Your Wealth Gate</h3>' => $slots['{{wealth_reading}}'],
            'in Your Love Thread</h3>' => $slots['{{love_reading}}'],
            'in Your Destiny Current</h3>' => $slots['{{life_reading}}'],
        ];

        foreach ($insertions as $needle => $block) {
            if ($block === '') {
                continue;
            }
            $html = $this->insertOnceAfter($html, $needle, $block);
        }

        return $html;
    }

    private function insertOnceAfter(string $html, string $needle, string $insertion): string
    {
        $pos = strpos($html, $needle);
        if ($pos === false) {
            return $html;
        }

        $insertAt = $pos + strlen($needle);
        if (str_contains(substr($html, $insertAt, strlen($insertion)), $insertion)) {
            return $html;
        }

        return substr($html, 0, $insertAt) . $insertion . substr($html, $insertAt);
    }

    private function formatReadingHtml(string $text): string
    {
        $text = trim($text);
        if ($text === '') {
            return '';
        }

        $escaped = htmlspecialchars($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        return '<div class="card-json-reading" style="margin:18px 0 8px;padding:16px 18px;background:rgba(138,106,31,.07);'
            . 'border-left:3px solid #8a6a1f;font-family:\'Cormorant Garamond\',serif;font-size:16.5px;'
            . 'line-height:1.65;color:#4a3a1a;"><p style="margin:0;">'
            . $escaped
            . '</p></div>';
    }

    private function resolveCardUrl(int|string|null $cardId, string $cardName): string
    {
        $id = $this->resolveCardId($cardId);
        if ($id >= 1) {
            $url = $this->cardImages->buildUrl($id);
            if ($url !== '') {
                return $url;
            }
        }

        return $this->cardImages->buildUrlForCardName($cardName);
    }

    private function resolveCardId(int|string|null $cardId): int
    {
        if ($cardId === null) {
            return 0;
        }
        if (is_int($cardId)) {
            return $cardId >= 1 ? $cardId : 0;
        }
        if ($cardId === '' || !is_numeric($cardId)) {
            return 0;
        }

        return max(0, (int) $cardId);
    }

    private function stripDevBar(string $html): string
    {
        return (string) preg_replace('/<div class="devbar">.*?<\/div>/s', '', $html);
    }
}
