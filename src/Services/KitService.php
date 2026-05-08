<?php

declare(strict_types=1);

namespace App\Services;

use App\Config\AppConfig;
use App\Domain\ClickBankSkuTags;
use GuzzleHttp\ClientInterface;

/**
 * Kit (ConvertKit) API v4: custom fields, subscribers, tags.
 */
final class KitService
{
    private const BASE = 'https://api.kit.com/v4/';

    /** @var list<array{label: string, key: string}> */
    private const REQUIRED_FIELDS = [
        ['label' => 'Date of Birth', 'key' => 'date_of_birth'],
        ['label' => 'Gender', 'key' => 'gender'],
        ['label' => 'Love Card', 'key' => 'love_card'],
        ['label' => 'Life Card', 'key' => 'life_card'],
        ['label' => 'Wealth Card', 'key' => 'wealth_card'],
        ['label' => 'Love Reading', 'key' => 'love_reading'],
        ['label' => 'Life Reading', 'key' => 'life_reading'],
        ['label' => 'Wealth Reading', 'key' => 'wealth_reading'],
        ['label' => 'Love Card Image', 'key' => 'love_card_image'],
        ['label' => 'Life Card Image', 'key' => 'life_card_image'],
        ['label' => 'Wealth Card Image', 'key' => 'wealth_card_image'],
        ['label' => 'Sun Sign', 'key' => 'sun_sign'],
        ['label' => 'Sun Personal Life', 'key' => 'sun_personal_life'],
        ['label' => 'Sun Profession', 'key' => 'sun_profession'],
        ['label' => 'Sun Health', 'key' => 'sun_health'],
        ['label' => 'Sun Emotions', 'key' => 'sun_emotions'],
        ['label' => 'Sun Travel', 'key' => 'sun_travel'],
        ['label' => 'Sun Luck', 'key' => 'sun_luck'],
    ];

    public function __construct(
        private readonly AppConfig $config,
        private readonly ClientInterface $http,
    ) {}

    /**
     * Ensures all custom fields used by Soul Mirror exist on the Kit account.
     */
    public function ensureCustomFields(): void
    {
        $res = $this->request('GET', 'custom_fields');
        $existing = [];
        foreach ($res['custom_fields'] ?? [] as $f) {
            if (is_array($f) && isset($f['key']) && is_string($f['key'])) {
                $existing[$f['key']] = true;
            }
        }
        foreach (self::REQUIRED_FIELDS as $field) {
            if (!isset($existing[$field['key']])) {
                $this->request('POST', 'custom_fields', ['label' => $field['label']]);
                error_log('Created KIT custom field: ' . $field['label']);
            }
        }
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
     * } $data
     */
    public function upsertSubscriber(array $data): void
    {
        $body = [
            'email_address' => $data['email'],
            'first_name' => $data['name'],
            'fields' => [
                'date_of_birth' => $data['dob'],
                'gender' => $data['gender'],
                'love_card' => $data['card1Name'],
                'life_card' => $data['card2Name'],
                'wealth_card' => $data['card3Name'],
                'love_reading' => $data['loveReading'],
                'life_reading' => $data['lifeReading'],
                'wealth_reading' => $data['wealthReading'],
                'love_card_image' => $data['loveCardImage'],
                'life_card_image' => $data['lifeCardImage'],
                'wealth_card_image' => $data['wealthCardImage'],
                'sun_sign' => $data['sunSign'],
                'sun_personal_life' => $data['sunPersonalLife'],
                'sun_profession' => $data['sunProfession'],
                'sun_health' => $data['sunHealth'],
                'sun_emotions' => $data['sunEmotions'],
                'sun_travel' => $data['sunTravel'],
                'sun_luck' => $data['sunLuck'],
            ],
        ];
        $this->request('POST', 'subscribers', $body);
    }

    public function tagSubscriber(string $email, string $tagName): void
    {
        if ($email === '') {
            return;
        }
        $name = strtolower(trim($tagName));
        if ($name === '') {
            return;
        }
        $this->tagEmailWithTagNames($email, [$name]);
    }

    /**
     * Subscribes the buyer email to Kit tags derived from ClickBank INS line items (lowercase SKUs).
     * Always includes the configured buyer tag ({@see AppConfig::kitTagNameBuyer}, from `KIT_TAG_NAME_BUYER`).
     * List missing tags, create via API, then add the subscriber — same flow as legacy ConvertKit v3 helpers.
     *
     * @param array<int, array<string, mixed>> $lineItems
     */
    public function subscribeClickBankBuyer(string $email, array $lineItems): void
    {
        if ($this->config->kitApiKey === '') {
            return;
        }
        if ($email === '') {
            return;
        }
        $buyerTag = strtolower(trim($this->config->kitTagNameBuyer));
        $skus = ClickBankSkuTags::distinctSkuTagNames($lineItems);
        $tagsToApply = $buyerTag === '' ? $skus : array_merge([$buyerTag], $skus);
        if ($tagsToApply === []) {
            return;
        }
        $this->tagEmailWithTagNames($email, $tagsToApply);
    }

    /**
     * @param list<string> $tagNames Deduped or not; trimmed and matched case-insensitively against Kit.
     */
    private function tagEmailWithTagNames(string $email, array $tagNames): void
    {
        // Ensure email exists as a Kit subscriber before applying any tags.
        $this->request('POST', 'subscribers', ['email_address' => $email]);

        $normalized = [];
        foreach ($tagNames as $tagName) {
            $lower = strtolower(trim($tagName));
            if ($lower !== '') {
                $normalized[$lower] = true;
            }
        }
        $unique = array_keys($normalized);
        if ($unique === []) {
            return;
        }

        $tagsRes = $this->request('GET', 'tags');
        $byLower = [];
        foreach ($tagsRes['tags'] ?? [] as $t) {
            if (!is_array($t)) {
                continue;
            }
            $nm = isset($t['name']) ? strtolower(trim((string) $t['name'])) : '';
            if ($nm !== '') {
                $byLower[$nm] = $t;
            }
        }

        foreach ($unique as $tagLower) {
            $tag = $byLower[$tagLower] ?? null;
            if ($tag === null) {
                $created = $this->request('POST', 'tags', ['name' => $tagLower]);
                $tag = $created['tag'] ?? null;
                if (is_array($tag) && isset($tag['id'])) {
                    $nm = isset($tag['name']) ? strtolower(trim((string) $tag['name'])) : '';
                    if ($nm !== '') {
                        $byLower[$nm] = $tag;
                    }
                    error_log('Created KIT tag: ' . $tagLower);
                }
            }
            if (!is_array($tag) || !isset($tag['id'])) {
                continue;
            }
            $tagId = $tag['id'];
            $this->request(
                'POST',
                'tags/' . rawurlencode((string) $tagId) . '/subscribers',
                ['email_address' => $email]
            );
        }
    }

    /**
     * @param array<string, mixed>|null $json
     *
     * @return array<string, mixed>
     */
    private function request(string $method, string $endpoint, ?array $json = null): array
    {
        $options = [
            'headers' => [
                'X-Kit-Api-Key' => $this->config->kitApiKey,
                'Content-Type' => 'application/json',
            ],
            'http_errors' => false,
        ];
        if ($json !== null) {
            $options['json'] = $json;
        }
        $response = $this->http->request($method, self::BASE . $endpoint, $options);
        $status = $response->getStatusCode();
        $raw = $response->getBody()->getContents();
        if ($status < 200 || $status >= 300) {
            throw new KitApiException($status, self::formatErrorSummary($status, $raw));
        }
        if ($raw === '') {
            return [];
        }
        $decoded = json_decode($raw, true);

        return is_array($decoded) ? $decoded : [];
    }

    private static function formatErrorSummary(int $status, string $raw): string
    {
        $prefix = 'Kit API HTTP ' . (string) $status;
        if ($raw === '') {
            return $prefix;
        }
        try {
            /** @var mixed $data */
            $data = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException) {
            $normalized = preg_replace('/\s+/', ' ', $raw);
            $snippet = substr(is_string($normalized) ? $normalized : $raw, 0, 200);

            return $prefix . ': ' . $snippet;
        }
        if (!is_array($data)) {
            return $prefix;
        }
        if (isset($data['errors'])) {
            $e = $data['errors'];
            if (is_string($e) && $e !== '') {
                return $prefix . ': ' . $e;
            }
            if (is_array($e)) {
                $parts = [];
                foreach ($e as $item) {
                    if (is_string($item)) {
                        $parts[] = $item;
                    } elseif (is_array($item) && isset($item['message']) && is_string($item['message'])) {
                        $parts[] = $item['message'];
                    }
                }
                if ($parts !== []) {
                    return $prefix . ': ' . implode('; ', $parts);
                }
            }
        }
        if (isset($data['message']) && is_string($data['message']) && $data['message'] !== '') {
            return $prefix . ': ' . $data['message'];
        }

        return $prefix;
    }
}
