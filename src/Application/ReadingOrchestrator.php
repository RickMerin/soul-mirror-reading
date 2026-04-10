<?php

declare(strict_types=1);

namespace App\Application;

use App\Config\AppConfig;
use App\Domain\CardImageUrlBuilder;
use App\Domain\SunSignResolver;
use App\Logging\PipelineLogger;
use App\Services\AstrologyApiClient;
use App\Services\AstrologyApiException;
use App\Services\KitService;
use Throwable;

/**
 * Orchestrates validation → AstrologyAPI (tarot + optional sun) → Kit subscriber + tag.
 *
 * This is the single application use-case for submitting a three-card reading.
 */
final class ReadingOrchestrator
{
    public function __construct(
        private readonly AppConfig $config,
        private readonly AstrologyApiClient $astrology,
        private readonly KitService $kit,
        private readonly SunSignResolver $sunSignResolver,
        private readonly CardImageUrlBuilder $cardImages,
        private readonly PipelineLogger $pipelineLog,
    ) {}

    /**
     * @param array<string, mixed> $body Decoded JSON request body
     */
    public function run(array $body): ReadingResult
    {
        $this->pipelineLog->envSummary();

        $name = $this->string($body, 'name');
        $email = $this->string($body, 'email');
        $dob = $this->string($body, 'dob');
        $gender = $this->string($body, 'gender');
        $card1 = $body['card1'] ?? null;
        $card2 = $body['card2'] ?? null;
        $card3 = $body['card3'] ?? null;
        $card1Name = $this->string($body, 'card1Name');
        $card2Name = $this->string($body, 'card2Name');
        $card3Name = $this->string($body, 'card3Name');

        if ($name === '' || $email === '' || $card1 === null || $card2 === null || $card3 === null) {
            $this->pipelineLog->line('validation: fail (missing required fields)');

            return new ReadingResult(400, ['error' => 'Missing required fields.']);
        }

        if ($this->config->astroUserId === '' || $this->config->astroApiKey === '') {
            error_log('ReadingOrchestrator: AstrologyAPI credentials missing');
            $this->pipelineLog->line('abort: AstrologyAPI credentials missing');

            return new ReadingResult(500, ['error' => 'Internal server error.']);
        }

        if ($this->config->kitApiKey === '') {
            error_log('ReadingOrchestrator: KIT_API_KEY missing');
            $this->pipelineLog->line('abort: KIT_API_KEY missing');

            return new ReadingResult(500, ['error' => 'Internal server error.']);
        }

        $c1 = (int) $card1;
        $c2 = (int) $card2;
        $c3 = (int) $card3;

        try {
            $reading = $this->astrology->fetchTarotPredictions($c1, $c2, $c3);
            $this->pipelineLog->line('tarot_predictions: ok');
        } catch (AstrologyApiException $e) {
            $this->pipelineLog->line('tarot_predictions: fail (' . $e->getMessage() . ')');

            return new ReadingResult(502, ['error' => $e->getMessage()]);
        } catch (Throwable $e) {
            error_log('ReadingOrchestrator tarot: ' . $e->getMessage());
            $this->pipelineLog->line('tarot_predictions: error ' . $e::class . ' ' . $this->shortSafeMessage($e));

            return new ReadingResult(500, ['error' => 'Internal server error.']);
        }

        $loveText = is_string($reading['love'] ?? null) ? $reading['love'] : '';
        $lifeText = is_string($reading['career'] ?? null) ? $reading['career'] : '';
        $wealthText = is_string($reading['finance'] ?? null) ? $reading['finance'] : '';

        $sunSign = $this->sunSignResolver->fromDobString($dob !== '' ? $dob : null);
        $sunPrediction = [];
        if ($sunSign !== null) {
            $sunPrediction = $this->astrology->fetchSunPrediction($sunSign);
            error_log('Sun sign resolved for reading request: ' . $sunSign);
            $nonEmpty = array_filter($sunPrediction, static fn (string $v): bool => $v !== '');
            $this->pipelineLog->line('sun_sign_prediction: sign=' . $sunSign . ' fields_filled=' . (string) count($nonEmpty));
        } else {
            $this->pipelineLog->line('sun_sign_prediction: skipped (no sign from DOB)');
        }

        $subscriber = [
            'name' => $name,
            'email' => $email,
            'dob' => $dob,
            'gender' => $gender,
            'card1Name' => $card1Name,
            'card2Name' => $card2Name,
            'card3Name' => $card3Name,
            'loveReading' => $loveText,
            'lifeReading' => $lifeText,
            'wealthReading' => $wealthText,
            'loveCardImage' => $this->cardImages->buildUrl($card1),
            'lifeCardImage' => $this->cardImages->buildUrl($card2),
            'wealthCardImage' => $this->cardImages->buildUrl($card3),
            'sunSign' => $sunSign ?? '',
            'sunPersonalLife' => $sunPrediction['personal_life'] ?? '',
            'sunProfession' => $sunPrediction['profession'] ?? '',
            'sunHealth' => $sunPrediction['health'] ?? '',
            'sunEmotions' => $sunPrediction['emotions'] ?? '',
            'sunTravel' => $sunPrediction['travel'] ?? '',
            'sunLuck' => $sunPrediction['luck'] ?? '',
        ];

        try {
            $this->kit->ensureCustomFields();
            $this->pipelineLog->line('kit: custom_fields ensured');
            $this->kit->upsertSubscriber($subscriber);
            $this->pipelineLog->line('kit: subscriber upsert ok');
            $this->kit->tagSubscriber($email, $this->config->kitTagName);
            $this->pipelineLog->line('kit: tag applied tag=' . $this->config->kitTagName);
        } catch (Throwable $e) {
            error_log('ReadingOrchestrator Kit: ' . $e->getMessage());
            $this->pipelineLog->line('kit: fail ' . $e::class . ' ' . $this->shortSafeMessage($e));

            return new ReadingResult(500, ['error' => 'Internal server error.']);
        }

        error_log('Reading pipeline completed (subscriber upsert + tag).');
        $this->pipelineLog->line('pipeline: complete HTTP 200');

        return new ReadingResult(200, ['success' => true]);
    }

    /**
     * @param array<string, mixed> $body
     */
    private function string(array $body, string $key): string
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

    private function shortSafeMessage(Throwable $e): string
    {
        return substr($e->getMessage(), 0, 200);
    }
}
