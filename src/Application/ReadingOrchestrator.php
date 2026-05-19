<?php

declare(strict_types=1);

namespace App\Application;

use App\Config\AppConfig;
use App\Domain\CardImageUrlBuilder;
use App\Domain\FormSubmission;
use App\Domain\KitEmbedFieldMap;
use App\Domain\SunSignResolver;
use App\Logging\PipelineLogger;
use App\Repository\LeadRepository;
use App\Services\AstrologyApiException;
use App\Services\KitApiException;
use App\Services\KitService;
use App\Services\SunSignPredictionProvider;
use App\Services\TarotPredictionProvider;
use Throwable;

/**
 * Orchestrates validation → tarot + sun readings → Kit subscriber + tag.
 */
final class ReadingOrchestrator
{
    public function __construct(
        private readonly AppConfig $config,
        private readonly TarotPredictionProvider $tarot,
        private readonly SunSignPredictionProvider $sunSign,
        private readonly KitService $kit,
        private readonly SunSignResolver $sunSignResolver,
        private readonly CardImageUrlBuilder $cardImages,
        private readonly PipelineLogger $pipelineLog,
        private readonly ?LeadRepository $leadRepository = null,
    ) {}

    /**
     * @param array<string, mixed> $body Decoded JSON request body
     */
    public function run(array $body): ReadingResult
    {
        $this->pipelineLog->envSummary();

        $form = FormSubmission::tryCreate($body);
        if ($form === null) {
            $this->pipelineLog->line('validation: fail (missing required fields)');

            return new ReadingResult(400, ['error' => 'Missing required fields.']);
        }

        $name = $form->name;
        $email = $form->email;
        $dob = $form->dob;
        $gender = $form->gender;
        $card1Name = $form->card1Name;
        $card2Name = $form->card2Name;
        $card3Name = $form->card3Name;
        $c1 = $form->card1;
        $c2 = $form->card2;
        $c3 = $form->card3;

        if ($this->config->needsAstroApiCredentials()
            && ($this->config->astroUserId === '' || $this->config->astroApiKey === '')) {
            error_log('ReadingOrchestrator: AstrologyAPI credentials missing');
            $this->pipelineLog->line('abort: AstrologyAPI credentials missing');

            return new ReadingResult(500, ['error' => 'Internal server error.']);
        }

        if ($this->config->kitApiKey === '' && $this->config->kitFormSubscribeVia !== 'none') {
            error_log('ReadingOrchestrator: KIT_API_KEY missing');
            $this->pipelineLog->line('abort: KIT_API_KEY missing');

            return new ReadingResult(500, ['error' => 'Internal server error.']);
        }

        $cards = [
            ['id' => $c1, 'name' => $card1Name],
            ['id' => $c2, 'name' => $card2Name],
            ['id' => $c3, 'name' => $card3Name],
        ];
        $leadUuid = null;
        if ($this->leadRepository !== null) {
            try {
                $leadUuid = $this->leadRepository->upsertLead(
                    email: $email,
                    name: $name,
                    dob: $dob,
                    gender: $gender,
                    cards: $cards,
                );
                $this->pipelineLog->line('lead: upsert ok');
            } catch (Throwable $e) {
                $this->pipelineLog->line('lead: fail ' . $e::class . ' ' . $this->shortSafeMessage($e));

                return new ReadingResult(500, ['error' => 'Internal server error.']);
            }
        }

        try {
            $reading = $this->tarot->predict($c1, $c2, $c3);
            $this->pipelineLog->line('tarot_predictions: ok source=' . $this->config->tarotSource);
        } catch (AstrologyApiException $e) {
            $this->pipelineLog->line('tarot_predictions: fail (' . $e->getMessage() . ')');

            return new ReadingResult(502, ['error' => $e->getMessage()]);
        } catch (Throwable $e) {
            error_log('ReadingOrchestrator tarot: ' . $e->getMessage());
            $this->pipelineLog->line('tarot_predictions: error ' . $e::class . ' ' . $this->shortSafeMessage($e));

            return new ReadingResult(500, ['error' => 'Internal server error.']);
        }

        $loveText = $reading->love;
        $lifeText = $reading->career;
        $wealthText = $reading->finance;

        $sunSign = $this->sunSignResolver->fromDobString($dob !== '' ? $dob : null);
        $sunPrediction = [];
        if ($sunSign !== null) {
            $sunPrediction = $this->sunSign->forSign($sunSign);
            error_log('Sun sign resolved for reading request: ' . $sunSign);
            $nonEmpty = array_filter($sunPrediction, static fn (string $v): bool => $v !== '');
            $this->pipelineLog->line(
                'sun_sign_prediction: sign=' . $sunSign
                . ' source=' . $this->config->sunSource
                . ' fields_filled=' . (string) count($nonEmpty),
            );
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
            'loveCardImage' => $this->cardImages->buildUrl($c1),
            'lifeCardImage' => $this->cardImages->buildUrl($c2),
            'wealthCardImage' => $this->cardImages->buildUrl($c3),
            'sunSign' => $sunSign ?? '',
            'sunPersonalLife' => $sunPrediction['personal_life'] ?? '',
            'sunProfession' => $sunPrediction['profession'] ?? '',
            'sunHealth' => $sunPrediction['health'] ?? '',
            'sunEmotions' => $sunPrediction['emotions'] ?? '',
            'sunTravel' => $sunPrediction['travel'] ?? '',
            'sunLuck' => $sunPrediction['luck'] ?? '',
        ];

        if ($this->config->kitFormSubscribeVia === 'none') {
            $this->pipelineLog->line('kit: skipped (strategy=none)');
        } else {
            if ($loveText === '' || $lifeText === '' || $wealthText === '') {
                $this->pipelineLog->line('kit: warning one or more tarot reading slots are empty');
            }
            try {
                $this->syncKitSubscriberWithReadings($subscriber, $email);
                if ($this->config->kitFormSubscribeVia === 'embed') {
                    $this->pipelineLog->line('kit: embed handoff (browser submits form with kitEmbedFields)');
                }
            } catch (Throwable $e) {
                error_log('ReadingOrchestrator Kit: ' . $e->getMessage());
                $this->pipelineLog->line('kit: fail ' . $e::class . ' ' . $this->shortSafeMessage($e));

                return new ReadingResult(500, ['error' => 'Internal server error.']);
            }
        }

        error_log('Reading pipeline completed.');
        $this->pipelineLog->line('pipeline: complete HTTP 200');

        if ($this->leadRepository !== null) {
            try {
                $this->leadRepository->upsertLead(
                    email: $email,
                    name: $name,
                    dob: $dob,
                    gender: $gender,
                    cards: $cards,
                    readingPayload: [
                        'love' => $loveText,
                        'life' => $lifeText,
                        'wealth' => $wealthText,
                        'sun_sign' => $sunSign,
                    ],
                );
            } catch (Throwable) {
                // Non-fatal here; lead row already exists from the initial capture.
            }
        }

        return new ReadingResult(200, [
            'success' => true,
            'leadUuid' => $leadUuid,
            'kitEmbedFields' => KitEmbedFieldMap::fromSubscriber($subscriber),
        ]);
    }

    /**
     * @param array{
     *   name: string,
     *   email: string,
     *   dob: string,
     *   gender: string,
     *   card1Name: string,
     *   card2Name: string,
     *   card3Name: string,
     *   loveReading: string,
     *   lifeReading: string,
     *   wealthReading: string,
     *   loveCardImage: string,
     *   lifeCardImage: string,
     *   wealthCardImage: string,
     *   sunSign: string,
     *   sunPersonalLife: string,
     *   sunProfession: string,
     *   sunHealth: string,
     *   sunEmotions: string,
     *   sunTravel: string,
     *   sunLuck: string,
     * } $subscriber
     */
    private function syncKitSubscriberWithReadings(array $subscriber, string $email): void
    {
        $this->kit->ensureCustomFields();
        $this->pipelineLog->line('kit: custom_fields ensured');
        $this->kit->upsertSubscriber($subscriber);
        $this->pipelineLog->line('kit: subscriber upsert ok (readings on custom fields)');
        if ($this->config->kitFormSubscribeVia === 'api' && $this->config->kitFormUid !== '') {
            try {
                $this->kit->subscribeLeadToConfiguredForm($email);
                $this->pipelineLog->line('kit: form_subscribe ok uid=' . $this->config->kitFormUid);
            } catch (KitApiException $e) {
                $this->pipelineLog->line('kit: form_subscribe fail (non-fatal) ' . $this->shortSafeMessage($e));
                error_log('ReadingOrchestrator Kit form subscribe: ' . $e->getMessage());
            }
        } elseif ($this->config->kitFormSubscribeVia === 'api') {
            $this->pipelineLog->line('kit: form_subscribe skipped (KIT_FORM_UID empty)');
        }
        $this->kit->tagSubscriber($email, $this->config->kitTagName, false);
        $this->pipelineLog->line('kit: tag applied tag=' . $this->config->kitTagName);
    }

    private function shortSafeMessage(Throwable $e): string
    {
        return substr($e->getMessage(), 0, 200);
    }
}
