<?php

declare(strict_types=1);

namespace App\Domain;

/**
 * Parsed and validated reading form payload (JSON body).
 */
final class FormSubmission
{
    private const MAX_NAME_LENGTH = 120;
    private const MAX_EMAIL_LENGTH = 254;
    private const MAX_CARD_NAME_LENGTH = 120;
    private const MIN_CARD_ID = 1;
    private const MAX_CARD_ID = 78;

    private function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $dob,
        public readonly string $gender,
        public readonly int $card1,
        public readonly int $card2,
        public readonly int $card3,
        public readonly string $card1Name,
        public readonly string $card2Name,
        public readonly string $card3Name,
    ) {}

    /**
     * @param array<string, mixed> $body Decoded JSON request body
     */
    public static function tryCreate(array $body): ?self
    {
        $name = self::validatedName(self::stringField($body, 'name'));
        $email = self::validatedEmail(self::stringField($body, 'email'));
        $dob = self::validatedDob(self::stringField($body, 'dob'));
        $gender = self::validatedGender(self::stringField($body, 'gender'));
        $card1Name = self::boundedText(self::stringField($body, 'card1Name'), self::MAX_CARD_NAME_LENGTH);
        $card2Name = self::boundedText(self::stringField($body, 'card2Name'), self::MAX_CARD_NAME_LENGTH);
        $card3Name = self::boundedText(self::stringField($body, 'card3Name'), self::MAX_CARD_NAME_LENGTH);
        $card1 = self::validatedCardId($body['card1'] ?? null);
        $card2 = self::validatedCardId($body['card2'] ?? null);
        $card3 = self::validatedCardId($body['card3'] ?? null);

        if ($name === null || $email === null || $dob === null || $gender === null || $card1 === null || $card2 === null || $card3 === null) {
            return null;
        }

        return new self(
            $name,
            $email,
            $dob,
            $gender,
            $card1,
            $card2,
            $card3,
            $card1Name,
            $card2Name,
            $card3Name,
        );
    }

    /**
     * @param array<string, mixed> $body
     */
    private static function stringField(array $body, string $key): string
    {
        $v = $body[$key] ?? '';
        if (is_string($v)) {
            return trim($v);
        }
        if (is_numeric($v)) {
            return trim((string) $v);
        }

        return '';
    }

    private static function validatedName(string $value): ?string
    {
        if (strlen($value) < 2 || strlen($value) > self::MAX_NAME_LENGTH) {
            return null;
        }
        if (!preg_match('/^[\p{L}\p{M}][\p{L}\p{M}\s\'\.-]*$/u', $value)) {
            return null;
        }

        return $value;
    }

    private static function validatedEmail(string $value): ?string
    {
        if ($value === '' || strlen($value) > self::MAX_EMAIL_LENGTH) {
            return null;
        }

        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false ? $value : null;
    }

    private static function validatedDob(string $value): ?string
    {
        if (!preg_match('/^(0[1-9]|1[0-2])\/(0[1-9]|[12][0-9]|3[01])\/(\d{4})$/', $value, $matches)) {
            return null;
        }

        $month = (int) $matches[1];
        $day = (int) $matches[2];
        $year = (int) $matches[3];

        if (!checkdate($month, $day, $year)) {
            return null;
        }

        $currentYear = (int) date('Y');
        if ($year < $currentYear - 120 || $year > $currentYear) {
            return null;
        }

        return $value;
    }

    private static function validatedGender(string $value): ?string
    {
        return in_array($value, ['Female', 'Male'], true) ? $value : null;
    }

    private static function validatedCardId(mixed $value): ?int
    {
        if (is_int($value)) {
            $cardId = $value;
        } elseif (is_string($value) && preg_match('/^\d+$/', $value)) {
            $cardId = (int) $value;
        } else {
            return null;
        }

        if ($cardId < self::MIN_CARD_ID || $cardId > self::MAX_CARD_ID) {
            return null;
        }

        return $cardId;
    }

    private static function boundedText(string $value, int $maxLength): string
    {
        if (strlen($value) <= $maxLength) {
            return $value;
        }

        return substr($value, 0, $maxLength);
    }
}
