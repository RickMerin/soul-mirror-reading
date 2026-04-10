<?php

declare(strict_types=1);

namespace App\Domain;

/**
 * Maps calendar month/day to western zodiac sign slug used by AstrologyAPI URLs.
 *
 * Logic matches the historical Node implementation (month/day boundaries).
 */
final class SunSignResolver
{
    /**
     * @return non-empty-string|null API slug (e.g. `aries`) or null if DOB parts are unusable
     */
    public function fromDobString(?string $dob): ?string
    {
        if ($dob === null || $dob === '') {
            return null;
        }
        $parts = explode('/', $dob);
        if (count($parts) < 2) {
            return null;
        }
        $month = (int) $parts[0];
        $day = (int) $parts[1];
        if ($month < 1 || $month > 12 || $day < 1 || $day > 31) {
            return null;
        }

        return $this->fromMonthDay($month, $day);
    }

    /**
     * @return non-empty-string Lowercase sign name for `/sun_sign_prediction/daily/{sign}`
     */
    public function fromMonthDay(int $month, int $day): string
    {
        if (($month === 3 && $day >= 21) || ($month === 4 && $day <= 19)) {
            return 'aries';
        }
        if (($month === 4 && $day >= 20) || ($month === 5 && $day <= 20)) {
            return 'taurus';
        }
        if (($month === 5 && $day >= 21) || ($month === 6 && $day <= 20)) {
            return 'gemini';
        }
        if (($month === 6 && $day >= 21) || ($month === 7 && $day <= 22)) {
            return 'cancer';
        }
        if (($month === 7 && $day >= 23) || ($month === 8 && $day <= 22)) {
            return 'leo';
        }
        if (($month === 8 && $day >= 23) || ($month === 9 && $day <= 22)) {
            return 'virgo';
        }
        if (($month === 9 && $day >= 23) || ($month === 10 && $day <= 22)) {
            return 'libra';
        }
        if (($month === 10 && $day >= 23) || ($month === 11 && $day <= 21)) {
            return 'scorpio';
        }
        if (($month === 11 && $day >= 22) || ($month === 12 && $day <= 21)) {
            return 'sagittarius';
        }
        if (($month === 12 && $day >= 22) || ($month === 1 && $day <= 19)) {
            return 'capricorn';
        }
        if (($month === 1 && $day >= 20) || ($month === 2 && $day <= 18)) {
            return 'aquarius';
        }

        return 'pisces';
    }
}
