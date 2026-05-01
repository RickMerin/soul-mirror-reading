<?php

declare(strict_types=1);

namespace App\Tests;

use App\Domain\ClickBankInsSlackTable;
use PHPUnit\Framework\TestCase;

final class ClickBankInsSlackTableTest extends TestCase
{
    public function testBuildBlocksProducesTableWithHeadersTxnAndPayload(): void
    {
        $payload = [
            'transactionType' => 'SALE',
            'receipt' => 'ABC-123',
            'customer' => ['billing' => ['email' => 'buyer@example.com']],
        ];
        $blocks = ClickBankInsSlackTable::buildBlocks($payload, 'SALE', 10_000);

        self::assertCount(1, $blocks);
        self::assertSame('table', $blocks[0]['type']);
        self::assertArrayHasKey('column_settings', $blocks[0]);
        self::assertSame(
            [['is_wrapped' => true], ['is_wrapped' => true]],
            $blocks[0]['column_settings']
        );

        $rows = $blocks[0]['rows'];
        self::assertIsArray($rows);
        self::assertGreaterThanOrEqual(3, count($rows));

        $h0 = $rows[0][0];
        self::assertSame('rich_text', $h0['type']);
        self::assertSame('Field', $h0['elements'][0]['elements'][0]['text']);
        self::assertTrue($h0['elements'][0]['elements'][0]['style']['bold']);

        self::assertSame('raw_text', $rows[1][0]['type']);
        self::assertSame('transaction_type', $rows[1][0]['text']);
        self::assertSame('raw_text', $rows[1][1]['type']);
        self::assertSame('SALE', $rows[1][1]['text']);

        self::assertSame('payload_json', $rows[2][0]['text']);
        $decoded = json_decode($rows[2][1]['text'], true);
        self::assertIsArray($decoded);
        self::assertSame('SALE', $decoded['transactionType']);

        $truncRows = array_values(array_filter($rows, static fn (array $r): bool => isset($r[0]['text']) && $r[0]['text'] === 'payload_truncated'));
        self::assertCount(0, $truncRows);
    }

    public function testBuildBlocksAddsTruncationRowWhenJsonExceedsLimit(): void
    {
        $payload = ['x' => str_repeat('a', 500)];
        $blocks = ClickBankInsSlackTable::buildBlocks($payload, null, 80);
        $rows = $blocks[0]['rows'];

        self::assertSame('transaction_type', $rows[1][0]['text']);
        self::assertSame('(missing)', $rows[1][1]['text']);

        $last = $rows[count($rows) - 1];
        self::assertSame('payload_truncated', $last[0]['text']);
        self::assertStringStartsWith('yes; original_length=', $last[1]['text']);
        self::assertStringContainsString('…[truncated]', $rows[2][1]['text']);
    }

    public function testFallbackTextIncludesTxnAndReceipt(): void
    {
        $text = ClickBankInsSlackTable::fallbackText([], 'RFND', 'R-1');
        self::assertStringContainsString('txn_type=RFND', $text);
        self::assertStringContainsString('receipt=R-1', $text);
    }

    public function testFallbackTextUsesNullAndMissingPlaceholders(): void
    {
        $text = ClickBankInsSlackTable::fallbackText([], null, '');
        self::assertStringContainsString('txn_type=null', $text);
        self::assertStringContainsString('receipt=(missing)', $text);
    }
}
