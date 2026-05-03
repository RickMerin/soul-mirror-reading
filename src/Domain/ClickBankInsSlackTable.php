<?php

declare(strict_types=1);

namespace App\Domain;

/**
 * Builds a single Slack Block Kit {@see https://docs.slack.dev/reference/block-kit/blocks/table-block table block}
 * for ClickBank Instant Notification (INS) payloads.
 */
final class ClickBankInsSlackTable
{
    /** Avoid oversized webhook bodies when many line items are present. */
    private const MAX_PRODUCTS_CELL_CHARS = 3500;

    /**
     * @return list<array<string, mixed>>
     */
    public static function buildBlocks(
        array $payload,
        ?string $txnType,
        string $receipt = '',
    ): array {
        $receiptDisplay = self::resolveReceipt($receipt, $payload);
        $txnDisplay = self::resolveTxnType($txnType, $payload);
        $customer = self::formatCustomerSummary($payload);
        $payment = self::stringOrEmDash(self::scalarString(self::payloadValue($payload, ['paymentMethod'])));
        $totals = self::formatTotals($payload);
        $lineItemRows = self::buildLineItemRows($payload);
        $upsell = self::formatUpsellSummary($payload);
        $downsell = self::formatDownsellSummary($payload);

        $rows = [
            [
                self::richTextBoldCell('Field'),
                self::richTextBoldCell('Value'),
            ],
            [
                self::richTextBoldCell('🔖 Reference'),
                self::rawTextCell($receiptDisplay),
            ],
            [
                self::richTextBoldCell('📌 Transaction'),
                self::rawTextCell($txnDisplay),
            ],
            [
                self::richTextBoldCell('👤 Customer'),
                self::rawTextCell($customer),
            ],
            [
                self::richTextBoldCell('💳 Payment'),
                self::rawTextCell($payment),
            ],
            [
                self::richTextBoldCell('💰 Totals'),
                self::rawTextCell($totals),
            ],
        ];

        foreach ($lineItemRows as $lineValue) {
            $rows[] = [
                self::richTextBoldCell('🛒 Product'),
                self::rawTextCell($lineValue),
            ];
        }

        if ($upsell !== null) {
            $rows[] = [
                self::richTextBoldCell('⬆️ Upsell'),
                self::rawTextCell($upsell),
            ];
        }

        if ($downsell !== null) {
            $rows[] = [
                self::richTextBoldCell('⬇️ Downsell'),
                self::rawTextCell($downsell),
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
        $txn = self::resolveTxnType($txnType, $payload);
        $receiptTrim = $receipt !== '' ? $receipt : self::resolveReceipt('', $payload);
        $email = self::scalarString(self::payloadValue($payload, [
            'customer.billing.email',
            'customer.shipping.email',
            'customer.email',
            'billing.email',
            'email',
        ]));
        $firstTitle = self::firstLineItemTitle($payload);
        $totalStr = self::formatSingleLineTotal($payload);

        $parts = ['ClickBank INS', 'receipt=' . $receiptTrim, 'txn=' . $txn];
        if ($email !== null) {
            $parts[] = 'email=' . $email;
        }
        if ($firstTitle !== null) {
            $parts[] = 'product=' . $firstTitle;
        }
        if ($totalStr !== null) {
            $parts[] = 'total=' . $totalStr;
        }

        return implode(' · ', $parts);
    }

    private static function resolveReceipt(string $receipt, array $payload): string
    {
        $r = trim($receipt);
        if ($r !== '') {
            return $r;
        }
        $fromPayload = self::scalarString(self::payloadValue($payload, [
            'receipt',
            'order.receipt',
            'transaction.receipt',
            'transactionId',
            'txnId',
        ]));

        return $fromPayload !== null && $fromPayload !== '' ? $fromPayload : '(missing)';
    }

    private static function resolveTxnType(?string $txnType, array $payload): string
    {
        if (is_string($txnType)) {
            $t = trim($txnType);
            if ($t !== '') {
                return strtoupper($t);
            }
        }
        $v = self::payloadValue($payload, ['transactionType', 'txnType', 'transType', 'eventType']);
        if (is_string($v) && trim($v) !== '') {
            return strtoupper(trim($v));
        }

        return '(missing)';
    }

    private static function formatCustomerSummary(array $payload): string
    {
        $name = self::resolveCustomerName($payload);
        $email = self::scalarString(self::payloadValue($payload, [
            'customer.billing.email',
            'customer.shipping.email',
            'customer.email',
            'billing.email',
            'email',
        ]));
        $loc = self::resolveCustomerLocation($payload);

        $lines = [];
        if ($name !== null) {
            $lines[] = $name;
        }
        if ($email !== null) {
            $lines[] = $email;
        }
        if ($loc !== null) {
            $lines[] = $loc;
        }

        return $lines !== [] ? implode("\n", $lines) : '—';
    }

    private static function resolveCustomerName(array $payload): ?string
    {
        $full = self::scalarString(self::payloadValue($payload, [
            'customer.billing.fullName',
            'customer.shipping.fullName',
            'customer.fullName',
            'billing.fullName',
            'fullName',
        ]));
        if ($full !== null) {
            return $full;
        }

        foreach (['customer.billing', 'customer.shipping', 'customer'] as $prefix) {
            $first = self::scalarString(self::payloadValue($payload, ["{$prefix}.firstName"]));
            $last = self::scalarString(self::payloadValue($payload, ["{$prefix}.lastName"]));
            $combined = trim(sprintf('%s %s', (string) $first, (string) $last));
            if ($combined !== '') {
                return $combined;
            }
        }

        return null;
    }

    private static function resolveCustomerLocation(array $payload): ?string
    {
        foreach (['customer.billing', 'customer.shipping'] as $prefix) {
            $country = self::scalarString(self::payloadValue($payload, [
                "{$prefix}.address.country",
                "{$prefix}.address.countryCode",
            ]));
            $postal = self::scalarString(self::payloadValue($payload, [
                "{$prefix}.address.postalCode",
                "{$prefix}.address.zip",
            ]));
            if ($country !== null || $postal !== null) {
                $parts = array_filter([$country, $postal], static fn (?string $p): bool => $p !== null && $p !== '');

                return implode(' ', $parts);
            }
        }

        return null;
    }

    private static function formatTotals(array $payload): string
    {
        $currency = self::scalarString(self::payloadValue($payload, ['currency', 'order.currency']));
        $currency = $currency !== null ? strtoupper($currency) : '';
        $order = self::numericAmount(self::payloadValue($payload, ['totalOrderAmount', 'order.totalOrderAmount']));
        $account = self::numericAmount(self::payloadValue($payload, [
            'totalAccountAmount',
            'order.totalAccountAmount',
        ]));

        $lines = [];
        if ($order !== null) {
            $lines[] = 'Order: ' . self::formatMoney($order, $currency);
        }
        if ($account !== null) {
            $lines[] = 'Vendor / net: ' . self::formatMoney($account, $currency);
        }

        return $lines !== [] ? implode("\n", $lines) : '—';
    }

    private static function formatSingleLineTotal(array $payload): ?string
    {
        $currency = self::scalarString(self::payloadValue($payload, ['currency', 'order.currency']));
        $currency = $currency !== null ? strtoupper($currency) : '';
        $order = self::numericAmount(self::payloadValue($payload, ['totalOrderAmount', 'order.totalOrderAmount']));
        if ($order === null) {
            return null;
        }

        return self::formatMoney($order, $currency);
    }

    /**
     * @return list<string>
     */
    private static function buildLineItemRows(array $payload): array
    {
        $items = self::payloadValue($payload, ['lineItems', 'order.lineItems', 'items']);
        if (!is_array($items)) {
            return ['—'];
        }
        $list = array_values(array_filter($items, static fn ($item): bool => is_array($item)));
        if ($list === []) {
            return ['—'];
        }

        $out = [];
        $bufLen = 0;
        foreach ($list as $item) {
            /** @var array<string, mixed> $item */
            $line = self::formatLineItem($item);
            if ($bufLen + strlen($line) + 1 > self::MAX_PRODUCTS_CELL_CHARS && $out !== []) {
                $out[] = '… (additional lines omitted)';

                break;
            }
            $out[] = $line;
            $bufLen += strlen($line) + 1;
        }

        return $out;
    }

    /**
     * @param array<string, mixed> $item
     */
    private static function formatLineItem(array $item): string
    {
        $title = self::scalarString($item['productTitle'] ?? null) ?? '(no title)';
        $itemNo = self::scalarString($item['itemNo'] ?? null) ?? '';
        $type = self::scalarString($item['lineItemType'] ?? null) ?? '';
        $qty = self::numericAmount($item['quantity'] ?? null);
        $qtyStr = $qty !== null ? (string) (int) $qty : '1';
        $price = self::numericAmount($item['productPrice'] ?? null);

        $parts = [$title];
        if ($itemNo !== '') {
            $parts[] = '(' . $itemNo . ')';
        }
        if ($type !== '') {
            $parts[] = '[' . $type . ']';
        }
        $head = implode(' ', $parts);
        if ($price !== null) {
            return $head . ' ×' . $qtyStr . ' — ' . number_format($price, 2, '.', ',');
        }

        return $head . ' ×' . $qtyStr;
    }

    private static function formatUpsellSummary(array $payload): ?string
    {
        $u = $payload['upsell'] ?? null;
        if (!is_array($u) || $u === []) {
            return null;
        }
        $name = self::scalarString($u['upsellFlowName'] ?? null);
        $path = self::scalarString($u['upsellPath'] ?? null);
        $orig = self::scalarString($u['upsellOriginalReceipt'] ?? null);

        $bits = [];
        if ($name !== null) {
            $bits[] = $name;
        }
        if ($path !== null) {
            $bits[] = 'path=' . $path;
        }
        if ($orig !== null) {
            $bits[] = 'original=' . $orig;
        }

        return $bits !== [] ? implode(' · ', $bits) : null;
    }

    private static function formatDownsellSummary(array $payload): ?string
    {
        $d = self::payloadValue($payload, ['downsell', 'order.downsell']);
        if (!is_array($d) || $d === []) {
            return null;
        }
        $name = self::scalarString($d['downsellFlowName'] ?? null);
        $path = self::scalarString($d['downsellPath'] ?? null);
        $orig = self::scalarString($d['downsellOriginalReceipt'] ?? null);

        $bits = [];
        if ($name !== null) {
            $bits[] = $name;
        }
        if ($path !== null) {
            $bits[] = 'path=' . $path;
        }
        if ($orig !== null) {
            $bits[] = 'original=' . $orig;
        }

        return $bits !== [] ? implode(' · ', $bits) : null;
    }

    private static function firstLineItemTitle(array $payload): ?string
    {
        $items = self::payloadValue($payload, ['lineItems', 'order.lineItems', 'items']);
        if (!is_array($items) || $items === []) {
            return null;
        }
        $indexed = array_values(array_filter($items, static fn ($item): bool => is_array($item)));
        $first = $indexed[0] ?? null;

        return is_array($first) ? self::scalarString($first['productTitle'] ?? null) : null;
    }

    private static function formatMoney(float $amount, string $currency): string
    {
        $n = number_format($amount, 2, '.', ',');

        return $currency !== '' ? $n . ' ' . $currency : $n;
    }

    private static function stringOrEmDash(?string $s): string
    {
        return $s !== null && $s !== '' ? $s : '—';
    }

    private static function numericAmount(mixed $v): ?float
    {
        if (is_numeric($v)) {
            return round((float) $v, 2);
        }

        return null;
    }

    private static function scalarString(mixed $v): ?string
    {
        if (!is_string($v)) {
            return null;
        }
        $t = trim($v);

        return $t !== '' ? $t : null;
    }

    /**
     * @param list<string> $paths
     */
    private static function payloadValue(array $payload, array $paths): mixed
    {
        foreach ($paths as $path) {
            $value = self::payloadValueByPath($payload, $path);
            if ($value !== null) {
                return $value;
            }
        }

        return null;
    }

    private static function payloadValueByPath(array $payload, string $path): mixed
    {
        $current = $payload;
        foreach (explode('.', $path) as $segment) {
            if (!is_array($current) || !array_key_exists($segment, $current)) {
                return null;
            }
            $current = $current[$segment];
        }

        return $current;
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
