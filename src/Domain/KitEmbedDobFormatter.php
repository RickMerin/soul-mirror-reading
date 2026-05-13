<?php

declare(strict_types=1);

namespace App\Domain;

/**
 * Converts DOB strings between the unlock API format and Kit embed form values.
 */
final class KitEmbedDobFormatter
{
    /** @var list<string> */
    private const MONTHS = [
        'Jan',
        'Feb',
        'Mar',
        'Apr',
        'May',
        'Jun',
        'Jul',
        'Aug',
        'Sep',
        'Oct',
        'Nov',
        'Dec',
    ];

    public static function apiToKitEmbed(string $dob): string
    {
        if (!preg_match('/^(0[1-9]|1[0-2])\/(0[1-9]|[12][0-9]|3[01])\/(\d{4})$/', $dob, $matches)) {
            return $dob;
        }

        $month = (int) $matches[1];
        $day = (int) $matches[2];
        $year = $matches[3];

        return $day . '-' . self::MONTHS[$month - 1] . '-' . $year;
    }

    public static function kitEmbedToApi(string $dob): ?string
    {
        if (preg_match('/^(0[1-9]|1[0-2])\/(0[1-9]|[12][0-9]|3[01])\/(\d{4})$/', $dob, $matches)) {
            $month = (int) $matches[1];
            $day = (int) $matches[2];
            $year = (int) $matches[3];
            if (!checkdate($month, $day, $year)) {
                return null;
            }

            return $dob;
        }

        if (!preg_match('/^(\d{1,2})-([A-Za-z]{3})-(\d{4})$/', $dob, $matches)) {
            return null;
        }

        $day = (int) $matches[1];
        $monthAbbrev = $matches[2];
        $year = (int) $matches[3];
        $month = self::monthIndex($monthAbbrev);
        if ($month === null || !checkdate($month, $day, $year)) {
            return null;
        }

        return sprintf('%02d/%02d/%04d', $month, $day, $year);
    }

    private static function monthIndex(string $monthAbbrev): ?int
    {
        foreach (self::MONTHS as $index => $label) {
            if (strcasecmp($label, $monthAbbrev) === 0) {
                return $index + 1;
            }
        }

        return null;
    }
}
