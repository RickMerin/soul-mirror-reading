<?php

declare(strict_types=1);

namespace App\Domain;

/**
 * Foregrounds the buyer's Mirror Block type in the HTML template.
 *
 * The master template is authored with Type One in second person ("you") and
 * Types Two–Four in third person. This adapter toggles labels and voice per type.
 */
final class ReadingBlockTypeVoiceAdapter
{
    private const NOT_YOUR_TYPE_MARKER = 'Not your type · included for the full framework';

    private const PRIMARY_TYPE_ONE_KICKER = 'Your Mirror Block · Type One';

    /** @var array<int, non-empty-string> */
    private const TYPE_KICKER = [
        1 => 'Type One',
        2 => 'Type Two',
        3 => 'Type Three',
        4 => 'Type Four',
    ];

    /** @var array<int, non-empty-string> */
    private const TYPE_ORDINAL = [
        1 => 'first',
        2 => 'second',
        3 => 'third',
        4 => 'fourth',
    ];

    public function adapt(string $html, string $mirrorBlockSlug): string
    {
        $typeNumber = MirrorBlockCatalog::typeNumberForSlug($mirrorBlockSlug) ?? 1;

        $html = $this->adaptPrimaryDeepSection($html, $typeNumber);

        foreach ([2, 3, 4] as $number) {
            $html = $this->adaptFrameworkTypeSection($html, $number, $typeNumber);
        }

        return $this->adaptMirrorBlockMetaCopy($html, $typeNumber);
    }

    private function adaptPrimaryDeepSection(string $html, int $buyerTypeNumber): string
    {
        if ($buyerTypeNumber === 1) {
            return $html;
        }

        $section = $this->extractBetweenKickers($html, self::PRIMARY_TYPE_ONE_KICKER, self::TYPE_KICKER[2]);
        if ($section === null) {
            return $html;
        }

        [$fullMatch, $sectionHtml] = $section;
        $adapted = $this->toThirdPerson($sectionHtml);
        $adapted = $this->ensureNotYourTypeLabel($adapted);
        $adapted = str_replace(
            '<div class="kicker">' . self::PRIMARY_TYPE_ONE_KICKER . '</div>',
            '<div class="kicker">' . self::TYPE_KICKER[1] . '</div>',
            $adapted,
        );

        return str_replace($fullMatch, $adapted, $html);
    }

    private function adaptFrameworkTypeSection(string $html, int $sectionType, int $buyerTypeNumber): string
    {
        $kicker = self::TYPE_KICKER[$sectionType];
        $endKicker = $sectionType < 4 ? self::TYPE_KICKER[$sectionType + 1] : null;
        $section = $this->extractBetweenKickers($html, $kicker, $endKicker);
        if ($section === null) {
            return $html;
        }

        [$fullMatch, $sectionHtml] = $section;
        $adapted = $sectionHtml;

        if ($sectionType === $buyerTypeNumber) {
            $adapted = $this->removeNotYourTypeLabel($adapted);
            $adapted = $this->toSecondPerson($adapted);
            $adapted = str_replace(
                '<div class="kicker">' . $kicker . '</div>',
                '<div class="kicker">' . $this->primaryKickerLabel($sectionType) . '</div>',
                $adapted,
            );
        } else {
            $adapted = $this->ensureNotYourTypeLabel($adapted);
        }

        return str_replace($fullMatch, $adapted, $html);
    }

    private function adaptMirrorBlockMetaCopy(string $html, int $typeNumber): string
    {
        $ordinal = self::TYPE_ORDINAL[$typeNumber] ?? self::TYPE_ORDINAL[1];

        $html = str_replace(
            'Yours is the first, named precisely and addressed to you.',
            'Yours is the ' . $ordinal . ', named precisely and addressed to you.',
            $html,
        );
        $html = str_replace(
            'Your cards named yours, Sarah: the first one.',
            'Your cards named yours, Sarah: the ' . $ordinal . ' one.',
            $html,
        );

        return $html;
    }

    private function primaryKickerLabel(int $typeNumber): string
    {
        return 'Your Mirror Block · ' . (self::TYPE_KICKER[$typeNumber] ?? self::TYPE_KICKER[1]);
    }

    /**
     * @return array{0: non-empty-string, 1: non-empty-string}|null
     */
    private function extractBetweenKickers(string $html, string $startKicker, ?string $endKicker): ?array
    {
        $startNeedle = '<div class="kicker">' . $startKicker . '</div>';
        $start = strpos($html, $startNeedle);
        if ($start === false) {
            return null;
        }

        $searchFrom = $start + strlen($startNeedle);
        $end = strlen($html);

        if ($endKicker !== null) {
            $endNeedle = '<div class="kicker">' . $endKicker . '</div>';
            $endPos = strpos($html, $endNeedle, $searchFrom);
            if ($endPos !== false) {
                $end = $endPos;
            }
        } else {
            $partFour = strpos($html, '<div class="part">Part Four</div>', $searchFrom);
            if ($partFour !== false) {
                $end = $partFour;
            }
        }

        $sectionHtml = substr($html, $start, $end - $start);
        if (!is_string($sectionHtml) || $sectionHtml === '') {
            return null;
        }

        return [$sectionHtml, $sectionHtml];
    }

    private function removeNotYourTypeLabel(string $html): string
    {
        $pattern = '/<div style="[^"]*">'
            . preg_quote(self::NOT_YOUR_TYPE_MARKER, '/')
            . '<\/div>/';

        return (string) preg_replace($pattern, '', $html);
    }

    private function ensureNotYourTypeLabel(string $html): string
    {
        if (str_contains($html, self::NOT_YOUR_TYPE_MARKER)) {
            return $html;
        }

        $label = '<div style="font-family:Arial,sans-serif; font-size:10px; letter-spacing:.12em; text-transform:uppercase; color:#b09a6a; margin-bottom:6px;">'
            . self::NOT_YOUR_TYPE_MARKER
            . '</div>';

        return $label . $html;
    }

    private function toSecondPerson(string $html): string
    {
        $replacements = [
            'This person' => 'You',
            'this person' => 'you',
            'They are' => 'You are',
            'they are' => 'you are',
            'They get' => 'You get',
            'they get' => 'you get',
            'They ' => 'You ',
            'they ' => 'you ',
            'Their ' => 'Your ',
            'their ' => 'your ',
            'them' => 'you',
        ];

        return strtr($html, $replacements);
    }

    private function toThirdPerson(string $html): string
    {
        $replacements = [
            ' your type' => ' this type',
            ' Your type' => ' This type',
            ' you ' => ' they ',
            ' You ' => ' They ',
            ' your ' => ' their ',
            ' Your ' => ' Their ',
            'you.' => 'they.',
            'You.' => 'They.',
        ];

        return strtr($html, $replacements);
    }
}
