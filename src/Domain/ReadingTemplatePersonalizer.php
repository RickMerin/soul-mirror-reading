<?php

declare(strict_types=1);

namespace App\Domain;

/**
 * Fills the Soul Mirror Reading HTML template with buyer-specific data.
 */
final class ReadingTemplatePersonalizer
{
    public function __construct(
        private readonly SoulMirrorCardImageUrlBuilder $cardImages,
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

        $sampleCards = [
            $type['sampleWealthCard'],
            $type['sampleLoveCard'],
            $type['sampleLifeCard'],
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
            $buyer['wealthCardName'],
            $buyer['loveCardName'],
            $buyer['lifeCardName'],
            $buyer['wealthCardId'] ?? null,
            $buyer['loveCardId'] ?? null,
            $buyer['lifeCardId'] ?? null,
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

    private function injectCardImages(
        string $html,
        string $wealthName,
        string $loveName,
        string $lifeName,
        int|string|null $wealthId,
        int|string|null $loveId,
        int|string|null $lifeId,
    ): string {
        $urls = [
            $this->resolveCardUrl($wealthId, $wealthName),
            $this->resolveCardUrl($loveId, $loveName),
            $this->resolveCardUrl($lifeId, $lifeName),
        ];

        foreach ($urls as $url) {
            if ($url === '') {
                continue;
            }
            $html = preg_replace(
                '/<img([^>]*?)alt="' . preg_quote($wealthName, '/') . '"([^>]*?)src="[^"]*"/',
                '<img$1alt="' . $wealthName . '"$2src="' . $url . '"',
                $html,
                1,
            ) ?? $html;
        }

        return $html;
    }

    private function resolveCardUrl(int|string|null $cardId, string $cardName): string
    {
        if ($cardId !== null && (is_int($cardId) || (is_string($cardId) && $cardId !== ''))) {
            $url = $this->cardImages->buildUrl($cardId);
            if ($url !== '') {
                return $url;
            }
        }

        return $this->cardImages->buildUrlForCardName($cardName);
    }

    private function stripDevBar(string $html): string
    {
        return (string) preg_replace('/<div class="devbar">.*?<\/div>/s', '', $html);
    }
}
