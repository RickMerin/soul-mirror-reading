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

    /** @var array<int, non-empty-string> */
    private const TYPE_KICKER = [
        1 => 'Type One',
        2 => 'Type Two',
        3 => 'Type Three',
        4 => 'Type Four',
    ];

    public function adapt(string $html, string $mirrorBlockSlug): string
    {
        $typeNumber = MirrorBlockCatalog::typeNumberForSlug($mirrorBlockSlug) ?? 1;

        foreach (self::TYPE_KICKER as $number => $kicker) {
            $section = $this->extractTypeSection($html, $kicker);
            if ($section === null) {
                continue;
            }

            [$fullMatch, $sectionHtml] = $section;
            $adapted = $sectionHtml;

            if ($number === $typeNumber) {
                $adapted = $this->removeNotYourTypeLabel($adapted);
                if ($number !== 1) {
                    $adapted = $this->toSecondPerson($adapted);
                }
            } else {
                $adapted = $this->ensureNotYourTypeLabel($adapted);
                if ($number === 1) {
                    $adapted = $this->toThirdPerson($adapted);
                }
            }

            $html = str_replace($fullMatch, $adapted, $html);
        }

        return $html;
    }

    /**
     * @return array{0: non-empty-string, 1: non-empty-string}|null
     */
    private function extractTypeSection(string $html, string $kicker): ?array
    {
        $startNeedle = '<div class="kicker">' . $kicker . '</div>';
        $start = strpos($html, $startNeedle);
        if ($start === false) {
            return null;
        }

        $searchFrom = $start + strlen($startNeedle);
        $end = strlen($html);
        foreach (self::TYPE_KICKER as $otherKicker) {
            if ($otherKicker === $kicker) {
                continue;
            }
            $otherNeedle = '<div class="kicker">' . $otherKicker . '</div>';
            $otherPos = strpos($html, $otherNeedle, $searchFrom);
            if ($otherPos !== false && $otherPos < $end) {
                $end = $otherPos;
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
