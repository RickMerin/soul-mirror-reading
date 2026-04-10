<?php

declare(strict_types=1);

namespace App\Domain;

/**
 * Parsed and validated reading form payload (JSON body).
 */
final class FormSubmission
{
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
        $name = self::stringField($body, 'name');
        $email = self::stringField($body, 'email');
        $dob = self::stringField($body, 'dob');
        $gender = self::stringField($body, 'gender');
        $card1Name = self::stringField($body, 'card1Name');
        $card2Name = self::stringField($body, 'card2Name');
        $card3Name = self::stringField($body, 'card3Name');
        $card1 = $body['card1'] ?? null;
        $card2 = $body['card2'] ?? null;
        $card3 = $body['card3'] ?? null;

        if ($name === '' || $email === '' || $card1 === null || $card2 === null || $card3 === null) {
            return null;
        }

        return new self(
            $name,
            $email,
            $dob,
            $gender,
            (int) $card1,
            (int) $card2,
            (int) $card3,
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
}
