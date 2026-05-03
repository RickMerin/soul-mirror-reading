<?php

declare(strict_types=1);

namespace App\Services;

use App\Config\AppConfig;
use App\Domain\ClickBankInsSlackTable;
use GuzzleHttp\ClientInterface;
use JsonException;
use Throwable;

/**
 * Posts ClickBank INS details to a Slack Incoming Webhook (optional).
 */
final class SlackClickBankInsLogger
{
    public function __construct(
        private readonly AppConfig $config,
        private readonly ClientInterface $http,
    ) {}

    public function notify(array $payload, ?string $txnType, string $receipt): void
    {
        $url = $this->config->clickbankInsSlackWebhookUrl;
        if ($url === '') {
            return;
        }

        try {
            $body = [
                'text' => ClickBankInsSlackTable::fallbackText($payload, $txnType, $receipt),
                'blocks' => ClickBankInsSlackTable::buildBlocks($payload, $txnType, $receipt),
            ];
            $json = json_encode($body, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
        } catch (JsonException $e) {
            error_log('SlackClickBankInsLogger: JSON encode failed: ' . $e->getMessage());

            return;
        }

        try {
            $opts = $this->config->guzzleClientConfig();
            $opts['headers'] = ['Content-Type' => 'application/json; charset=utf-8'];
            $opts['body'] = $json;
            $opts['timeout'] = 8.0;

            $response = $this->http->post($url, $opts);
            $code = $response->getStatusCode();
            if ($code < 200 || $code >= 300) {
                error_log('SlackClickBankInsLogger: webhook returned HTTP ' . (string) $code);
            }
        } catch (Throwable $e) {
            error_log('SlackClickBankInsLogger: request failed: ' . $e->getMessage());
        }
    }
}
