<?php

declare(strict_types=1);

namespace App\Services;

use App\Domain\TarotPredictionResult;
use JsonException;
use RuntimeException;

/**
 * Loads tarot slot texts from data/tarot-predictions.json (frontend card ids).
 */
final class LocalTarotPredictionRepository implements TarotPredictionProvider
{
    /** @var array<string, array{slug?: string, name?: string, love?: string, career?: string, finance?: string}>|null */
    private ?array $cards = null;

    public function __construct(
        private readonly string $jsonPath,
    ) {}

    public function predict(int $loveCard, int $careerCard, int $financeCard): TarotPredictionResult
    {
        $cards = $this->loadCards();

        return new TarotPredictionResult(
            love: $this->slotText($cards, $loveCard, 'love'),
            career: $this->slotText($cards, $careerCard, 'career'),
            finance: $this->slotText($cards, $financeCard, 'finance'),
        );
    }

    /**
     * @return array<string, array{slug?: string, name?: string, love?: string, career?: string, finance?: string}>
     */
    private function loadCards(): array
    {
        if ($this->cards !== null) {
            return $this->cards;
        }

        if (!is_readable($this->jsonPath)) {
            throw new RuntimeException('Tarot predictions file is missing or not readable.');
        }

        $raw = file_get_contents($this->jsonPath);
        if ($raw === false) {
            throw new RuntimeException('Failed to read tarot predictions file.');
        }

        try {
            /** @var array<string, mixed> $data */
            $data = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new RuntimeException('Invalid tarot predictions JSON.', 0, $e);
        }

        $cards = $data['cards'] ?? null;
        if (!is_array($cards)) {
            throw new RuntimeException('Tarot predictions JSON must contain a "cards" object.');
        }

        /** @var array<string, array{slug?: string, name?: string, love?: string, career?: string, finance?: string}> $cards */
        $this->cards = $cards;

        return $this->cards;
    }

    /**
     * @param array<string, array{slug?: string, name?: string, love?: string, career?: string, finance?: string}> $cards
     */
    private function slotText(array $cards, int $frontendId, string $slot): string
    {
        $entry = $cards[(string) $frontendId] ?? null;
        if (!is_array($entry)) {
            return '';
        }
        $text = $entry[$slot] ?? '';

        return is_string($text) ? $text : '';
    }
}
