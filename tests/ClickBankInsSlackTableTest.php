<?php

declare(strict_types=1);

namespace App\Tests;

use App\Domain\ClickBankInsSlackTable;
use PHPUnit\Framework\TestCase;

final class ClickBankInsSlackTableTest extends TestCase
{
    /**
     * @return array<string, mixed>
     */
    private static function v7OriginalPayload(): array
    {
        return [
            'transactionType' => 'TEST_SALE',
            'currency' => 'PHP',
            'paymentMethod' => 'TEST',
            'receipt' => 'HTY7MF4E',
            'totalOrderAmount' => 39.49,
            'totalAccountAmount' => 33.22,
            'customer' => [
                'shipping' => [
                    'email' => 'buyer@example.com',
                    'firstName' => 'Test',
                    'lastName' => 'Name',
                    'fullName' => 'Test Name',
                    'address' => [
                        'country' => 'PH',
                        'postalCode' => '3467',
                    ],
                ],
                'billing' => [
                    'email' => 'buyer@example.com',
                    'fullName' => 'Test Name',
                    'address' => [],
                ],
            ],
            'lineItems' => [
                [
                    'itemNo' => 'smr-1',
                    'lineItemType' => 'ORIGINAL',
                    'productTitle' => 'Soul Mirror Reading',
                    'quantity' => 1,
                    'productPrice' => 39.49,
                ],
            ],
            'upsell' => [
                'upsellFlowId' => 63301,
                'upsellFlowName' => '1 Upsell 1 Downsell',
                'upsellOriginalReceipt' => 'HTY7MF4E',
                'upsellSession' => 'EADh7_RL57MZHEQB',
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    /**
     * @return array<string, mixed>
     */
    private static function v7DownsellPayload(): array
    {
        return [
            'transactionType' => 'TEST_SALE',
            'currency' => 'PHP',
            'paymentMethod' => 'TEST',
            'receipt' => 'HTY7MZ4E',
            'totalOrderAmount' => 67.0,
            'totalAccountAmount' => 57.0,
            'customer' => [
                'shipping' => [
                    'email' => 'buyer@example.com',
                    'fullName' => 'Test Name',
                    'address' => [
                        'country' => 'PH',
                        'postalCode' => '3467',
                    ],
                ],
                'billing' => [
                    'email' => 'buyer@example.com',
                    'fullName' => 'Test Name',
                ],
            ],
            'lineItems' => [
                [
                    'itemNo' => 'srp-1-ds',
                    'lineItemType' => 'DOWNSELL',
                    'productTitle' => 'Soul Ritual Practice (Downsell)',
                    'quantity' => 1,
                    'productPrice' => 67.0,
                ],
            ],
            'upsell' => [
                'upsellFlowName' => '1 Upsell 1 Downsell',
                'upsellOriginalReceipt' => 'HTY7MF4E',
                'upsellPath' => 'ad',
                'upsellSession' => 'EADh7_RL57MZHEQB',
            ],
            'downsell' => [
                'downsellFlowName' => '1 Upsell 1 Downsell',
                'downsellOriginalReceipt' => 'HTY7MF4E',
                'downsellPath' => 'd',
                'downsellSession' => 'EADh7_RL57MZHEQB',
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private static function v7UpsellPayload(): array
    {
        return [
            'transactionType' => 'TEST_SALE',
            'currency' => 'PHP',
            'paymentMethod' => 'TEST',
            'receipt' => 'HTY7MQ4E',
            'totalOrderAmount' => 103.52,
            'totalAccountAmount' => 88.72,
            'customer' => [
                'shipping' => [
                    'email' => 'buyer@example.com',
                    'fullName' => 'Test Name',
                    'address' => [
                        'country' => 'PH',
                        'postalCode' => '3467',
                    ],
                ],
                'billing' => [
                    'email' => 'buyer@example.com',
                    'fullName' => 'Test Name',
                ],
            ],
            'lineItems' => [
                [
                    'itemNo' => 'srp-1',
                    'lineItemType' => 'UPSELL',
                    'productTitle' => 'Soul Ritual Practice',
                    'quantity' => 1,
                    'productPrice' => 103.52,
                ],
            ],
            'upsell' => [
                'upsellFlowName' => '1 Upsell 1 Downsell',
                'upsellOriginalReceipt' => 'HTY7MF4E',
                'upsellPath' => 'a',
                'upsellSession' => 'EADh7_RL57MZHEQB',
            ],
        ];
    }

    private static function fieldLabelText(array $richTextCell): string
    {
        self::assertSame('rich_text', $richTextCell['type']);

        return $richTextCell['elements'][0]['elements'][0]['text'];
    }

    private static function valueText(array $rawCell): string
    {
        self::assertSame('raw_text', $rawCell['type']);

        return $rawCell['text'];
    }

    private static function portalUrl(array $cell): string
    {
        self::assertSame('rich_text', $cell['type']);
        self::assertSame('link', $cell['elements'][0]['elements'][0]['type']);

        return $cell['elements'][0]['elements'][0]['url'];
    }

    public function testBuildBlocksSummaryForOriginalSale(): void
    {
        $payload = self::v7OriginalPayload();
        $blocks = ClickBankInsSlackTable::buildBlocks($payload, 'TEST_SALE', 'HTY7MF4E');

        self::assertCount(1, $blocks);
        self::assertSame('table', $blocks[0]['type']);
        $rows = $blocks[0]['rows'];
        self::assertIsArray($rows);

        self::assertSame('Field', self::fieldLabelText($rows[0][0]));
        self::assertSame('Value', self::fieldLabelText($rows[0][1]));

        self::assertStringContainsString('Reference', self::fieldLabelText($rows[1][0]));
        self::assertSame('HTY7MF4E', self::valueText($rows[1][1]));

        self::assertStringContainsString('Transaction', self::fieldLabelText($rows[2][0]));
        self::assertSame('TEST_SALE', self::valueText($rows[2][1]));

        $customer = self::valueText($rows[3][1]);
        self::assertStringContainsString('Test Name', $customer);
        self::assertStringContainsString('buyer@example.com', $customer);
        self::assertStringContainsString('PH', $customer);
        self::assertStringContainsString('3467', $customer);

        self::assertSame('TEST', self::valueText($rows[4][1]));

        $totals = self::valueText($rows[5][1]);
        self::assertStringContainsString('39.49', $totals);
        self::assertStringContainsString('PHP', $totals);
        self::assertStringContainsString('33.22', $totals);
        self::assertStringContainsString('Vendor', $totals);

        $bought = self::valueText($rows[6][1]);
        self::assertStringContainsString('Soul Mirror Reading ×1', $bought);

        $portal = self::portalUrl($rows[7][1]);
        self::assertStringContainsString('/login.php?cemail=buyer%40example.com', $portal);

        $product = self::valueText($rows[8][1]);
        self::assertStringContainsString('Soul Mirror Reading', $product);
        self::assertStringContainsString('smr-1', $product);
        self::assertStringContainsString('ORIGINAL', $product);

        self::assertStringContainsString('Upsell', self::fieldLabelText($rows[9][0]));
        $upsell = self::valueText($rows[9][1]);
        self::assertStringContainsString('1 Upsell 1 Downsell', $upsell);
        self::assertStringContainsString('original=HTY7MF4E', $upsell);
    }

    public function testBuildBlocksUpsellLineItemTypeAndReceipt(): void
    {
        $payload = self::v7UpsellPayload();
        $blocks = ClickBankInsSlackTable::buildBlocks($payload, 'TEST_SALE', 'HTY7MQ4E');
        $rows = $blocks[0]['rows'];

        self::assertSame('HTY7MQ4E', self::valueText($rows[1][1]));

        $product = self::valueText($rows[8][1]);
        self::assertStringContainsString('Soul Ritual Practice', $product);
        self::assertStringContainsString('srp-1', $product);
        self::assertStringContainsString('UPSELL', $product);

        $upsell = self::valueText($rows[9][1]);
        self::assertStringContainsString('path=a', $upsell);
        self::assertStringContainsString('original=HTY7MF4E', $upsell);
    }

    public function testBuildBlocksIncludesDownsellRowAfterUpsell(): void
    {
        $payload = self::v7DownsellPayload();
        $blocks = ClickBankInsSlackTable::buildBlocks($payload, 'TEST_SALE', 'HTY7MZ4E');
        $rows = $blocks[0]['rows'];

        self::assertStringContainsString('Upsell', self::fieldLabelText($rows[9][0]));
        $upsell = self::valueText($rows[9][1]);
        self::assertStringContainsString('1 Upsell 1 Downsell', $upsell);

        self::assertStringContainsString('Downsell', self::fieldLabelText($rows[10][0]));
        $downsell = self::valueText($rows[10][1]);
        self::assertStringContainsString('1 Upsell 1 Downsell', $downsell);
        self::assertStringContainsString('path=d', $downsell);
        self::assertStringContainsString('original=HTY7MF4E', $downsell);
    }

    public function testBuildBlocksUsesPayloadReceiptWhenArgumentEmpty(): void
    {
        $payload = [
            'receipt' => 'FROM-PAYLOAD',
            'transactionType' => 'SALE',
            'lineItems' => [],
        ];
        $blocks = ClickBankInsSlackTable::buildBlocks($payload, 'SALE', '');
        self::assertSame('FROM-PAYLOAD', self::valueText($blocks[0]['rows'][1][1]));
    }

    public function testBuildBlocksMissingTxnTypeFallsBackToPayload(): void
    {
        $payload = ['transactionType' => 'RFND', 'receipt' => 'R1', 'lineItems' => []];
        $blocks = ClickBankInsSlackTable::buildBlocks($payload, null, 'R1');
        self::assertSame('RFND', self::valueText($blocks[0]['rows'][2][1]));
    }

    public function testBuildBlocksOmitsUpsellRowWhenAbsent(): void
    {
        $payload = [
            'receipt' => 'X1',
            'transactionType' => 'SALE',
            'lineItems' => [
                ['productTitle' => 'A', 'itemNo' => 'a-1', 'lineItemType' => 'ORIGINAL', 'quantity' => 1, 'productPrice' => 10.0],
            ],
            'currency' => 'USD',
            'totalOrderAmount' => 10.0,
        ];
        $blocks = ClickBankInsSlackTable::buildBlocks($payload, 'SALE', 'X1');
        $rows = $blocks[0]['rows'];
        $lastField = self::fieldLabelText($rows[count($rows) - 1][0]);
        self::assertStringContainsString('Product', $lastField);
        self::assertStringNotContainsString('Upsell', $lastField);
        self::assertStringNotContainsString('Downsell', $lastField);
    }

    public function testBuildBlocksPortalRowFallsBackToEmDashWithoutBuyerEmail(): void
    {
        $payload = [
            'receipt' => 'X1',
            'transactionType' => 'SALE',
            'lineItems' => [],
        ];
        $blocks = ClickBankInsSlackTable::buildBlocks($payload, 'SALE', 'X1');
        self::assertSame('—', self::valueText($blocks[0]['rows'][7][1]));
    }

    public function testFallbackTextIncludesReceiptTxnEmailProductTotal(): void
    {
        $payload = self::v7OriginalPayload();
        $text = ClickBankInsSlackTable::fallbackText($payload, 'TEST_SALE', 'HTY7MF4E');
        self::assertStringContainsString('receipt=HTY7MF4E', $text);
        self::assertStringContainsString('txn=TEST_SALE', $text);
        self::assertStringContainsString('buyer@example.com', $text);
        self::assertStringContainsString('Soul Mirror Reading', $text);
        self::assertStringContainsString('39.49', $text);
    }

    public function testFallbackTextUsesNullAndMissingPlaceholders(): void
    {
        $text = ClickBankInsSlackTable::fallbackText([], null, '');
        self::assertStringContainsString('receipt=(missing)', $text);
        self::assertStringContainsString('txn=(missing)', $text);
    }
}
