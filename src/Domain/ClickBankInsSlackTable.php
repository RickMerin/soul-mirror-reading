<?php

declare(strict_types=1);

namespace App\Domain;

/**
 * Builds a single Slack Block Kit {@see https://docs.slack.dev/reference/block-kit/blocks/table-block table block}
 * for ClickBank Instant Notification (INS) payloads.
 */
final class ClickBankInsSlackTable
{
    /** Conservative limit for JSON in one table cell (Slack message size constraints). */
    public const DEFAULT_MAX_PAYLOAD_JSON_CHARS = 2800;

    /**
     * @return list<array<string, mixed>>
     */
    public static function buildBlocks(
        array $payload,
        ?string $txnType,
        int $maxPayloadJsonChars = self::DEFAULT_MAX_PAYLOAD_JSON_CHARS,
    ): array {
        $json = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        if (!is_string($json)) {
            $json = '{}';
        }
        $originalLen = strlen($json);
        $truncated = $originalLen > $maxPayloadJsonChars;
        $jsonCell = $truncated
            ? substr($json, 0, $maxPayloadJsonChars) . '…[truncated]'
            : $json;

        $rows = [
            [
                self::richTextBoldCell('Field'),
                self::richTextBoldCell('Value'),
            ],
            [
                self::rawTextCell('transaction_type'),
                self::rawTextCell($txnType ?? '(missing)'),
            ],
            [
                self::rawTextCell('payload_json'),
                self::rawTextCell($jsonCell),
            ],
        ];

        if ($truncated) {
            $rows[] = [
                self::rawTextCell('payload_truncated'),
                self::rawTextCell('yes; original_length=' . (string) $originalLen),
            ];
        }

        return [
            [
                'type' => 'table',
                'column_settings' => [
                    ['is_wrapped' => true],
                    ['is_wrapped' => true],
                ],
                'rows' => $rows,
            ],
        ];
    }

    public static function fallbackText(array $payload, ?string $txnType, string $receipt): string
    {
        $txn = $txnType ?? 'null';
        $receiptTrim = $receipt !== '' ? $receipt : '(missing)';

        return 'ClickBank INS: txn_type=' . $txn . ' receipt=' . $receiptTrim;
    }

    /**
     * @return array<string, mixed>
     */
    private static function richTextBoldCell(string $text): array
    {
        return [
            'type' => 'rich_text',
            'elements' => [
                [
                    'type' => 'rich_text_section',
                    'elements' => [
                        [
                            'type' => 'text',
                            'text' => $text,
                            'style' => [
                                'bold' => true,
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array{type: string, text: string}
     */
    private static function rawTextCell(string $text): array
    {
        return [
            'type' => 'raw_text',
            'text' => $text,
        ];
    }
}
