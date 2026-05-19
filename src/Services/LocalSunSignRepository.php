<?php

declare(strict_types=1);

namespace App\Services;

use DateTimeImmutable;
use DateTimeZone;
use JsonException;

/**
 * Loads daily sun-sign predictions from data/sun-sign/YYYY-MM-DD.json.
 */
final class LocalSunSignRepository implements SunSignPredictionProvider
{
    /** @var list<non-empty-string> */
    private const FIELDS = [
        'personal_life',
        'profession',
        'health',
        'emotions',
        'travel',
        'luck',
    ];

    /** @var array<string, array<string, string>>|null */
    private ?array $signsForDate = null;

    public function __construct(
        private readonly string $sunSignDir,
        private readonly DateTimeZone $timezone,
    ) {}

    public function forSign(string $sign): array
    {
        $signs = $this->loadSignsForToday();
        $entry = $signs[$sign] ?? [];
        if (!is_array($entry)) {
            return $this->emptyFields();
        }

        $out = [];
        foreach (self::FIELDS as $field) {
            $v = $entry[$field] ?? '';
            $out[$field] = is_string($v) ? $v : (is_numeric($v) ? (string) $v : '');
        }

        return $out;
    }

    /**
     * @return array<string, array<string, string>>
     */
    private function loadSignsForToday(): array
    {
        if ($this->signsForDate !== null) {
            return $this->signsForDate;
        }

        $date = (new DateTimeImmutable('now', $this->timezone))->format('Y-m-d');
        $path = $this->sunSignDir . DIRECTORY_SEPARATOR . $date . '.json';

        if (!is_readable($path)) {
            $path = $this->latestSunSignFilePath();
            if ($path === null) {
                $this->signsForDate = [];

                return $this->signsForDate;
            }
        }

        $raw = file_get_contents($path);
        if ($raw === false) {
            $this->signsForDate = [];

            return $this->signsForDate;
        }

        try {
            /** @var array<string, mixed> $data */
            $data = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            $this->signsForDate = [];

            return $this->signsForDate;
        }

        $signs = $data['signs'] ?? null;
        if (!is_array($signs)) {
            $this->signsForDate = [];

            return $this->signsForDate;
        }

        /** @var array<string, array<string, string>> $signs */
        $this->signsForDate = $signs;

        return $this->signsForDate;
    }

    private function latestSunSignFilePath(): ?string
    {
        if (!is_dir($this->sunSignDir)) {
            return null;
        }
        $files = glob($this->sunSignDir . DIRECTORY_SEPARATOR . '*.json');
        if ($files === false || $files === []) {
            return null;
        }
        rsort($files, SORT_STRING);

        return is_readable($files[0]) ? $files[0] : null;
    }

    /**
     * @return array<string, string>
     */
    private function emptyFields(): array
    {
        $out = [];
        foreach (self::FIELDS as $field) {
            $out[$field] = '';
        }

        return $out;
    }
}
