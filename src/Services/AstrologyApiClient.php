<?php

declare(strict_types=1);

namespace App\Services;

use App\Config\AppConfig;
use GuzzleHttp\ClientInterface;
use JsonException;

/**
 * HTTP client for json.astrologyapi.com (Basic auth).
 */
final class AstrologyApiClient
{
    private const BASE = 'https://json.astrologyapi.com/v1/';

    public function __construct(
        private readonly AppConfig $config,
        private readonly ClientInterface $http,
    ) {}

    /**
     * POST /tarot_predictions — returns decoded JSON with `love`, `career`, `finance` text keys.
     *
     * @return array<string, mixed>
     *
     * @throws JsonException when the response body is not valid JSON
     */
    public function fetchTarotPredictions(int $love, int $career, int $finance): array
    {
        $auth = base64_encode($this->config->astroUserId . ':' . $this->config->astroApiKey);
        $response = $this->http->request('POST', self::BASE . 'tarot_predictions', [
            'http_errors' => false,
            'headers' => [
                'Authorization' => 'Basic ' . $auth,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'love' => $love,
                'career' => $career,
                'finance' => $finance,
            ],
        ]);

        $status = $response->getStatusCode();
        $raw = $response->getBody()->getContents();
        if ($status < 200 || $status >= 300) {
            error_log('AstrologyAPI tarot_predictions failed: HTTP ' . $status);
            throw new AstrologyApiException('Failed to fetch tarot reading.');
        }

        /** @var array<string, mixed> */
        return json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * POST /sun_sign_prediction/daily/{sign} — returns the `prediction` object or [] on failure.
     *
     * @return array<string, string>
     */
    public function fetchSunPrediction(string $sign): array
    {
        $auth = base64_encode($this->config->astroUserId . ':' . $this->config->astroApiKey);
        $response = $this->http->request('POST', self::BASE . 'sun_sign_prediction/daily/' . rawurlencode($sign), [
            'http_errors' => false,
            'headers' => [
                'Authorization' => 'Basic ' . $auth,
                'Content-Type' => 'application/json',
            ],
        ]);

        $status = $response->getStatusCode();
        $raw = $response->getBody()->getContents();
        if ($status < 200 || $status >= 300) {
            error_log('AstrologyAPI sun_sign_prediction failed: HTTP ' . $status);

            return [];
        }

        try {
            /** @var array<string, mixed> $data */
            $data = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            return [];
        }
        $pred = $data['prediction'] ?? null;

        return is_array($pred) ? $this->stringifyPrediction($pred) : [];
    }

    /**
     * @param array<string, mixed> $pred
     *
     * @return array<string, string>
     */
    private function stringifyPrediction(array $pred): array
    {
        $out = [];
        foreach ($pred as $k => $v) {
            if (is_string($k) && (is_string($v) || is_numeric($v))) {
                $out[$k] = (string) $v;
            }
        }

        return $out;
    }
}
