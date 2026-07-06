<?php

declare(strict_types=1);

namespace App\Tests;

use App\Domain\ClickBankInsStatusMapper;
use PHPUnit\Framework\TestCase;

final class ClickBankInsStatusMapperTest extends TestCase
{
    /**
     * @dataProvider txnTypeProvider
     */
    public function testFromTxnTypeMapsClickBankTypes(string $txnType, string $expected): void
    {
        self::assertSame($expected, ClickBankInsStatusMapper::fromTxnType($txnType));
    }

    /**
     * @return array<string, array{0: string, 1: string}>
     */
    public static function txnTypeProvider(): array
    {
        return [
            'sale' => ['SALE', 'approved'],
            'bill' => ['BILL', 'approved'],
            'billed alias' => ['BILLED', 'approved'],
            'test sale' => ['TEST_SALE', 'approved'],
            'test bill' => ['TEST_BILL', 'approved'],
            'uncancel rebill' => ['UNCANCEL-REBILL', 'approved'],
            'uncancel test rebill' => ['UNCANCEL-TEST-REBILL', 'approved'],
            'refund' => ['RFND', 'refunded'],
            'refund alias' => ['REFUND', 'refunded'],
            'test refund' => ['TEST_RFND', 'refunded'],
            'chargeback' => ['CGBK', 'chargeback'],
            'chargeback alias' => ['CHARGEBACK', 'chargeback'],
            'echeck chargeback' => ['INSF', 'chargeback'],
            'cancel rebill' => ['CANCEL-REBILL', 'cancelled'],
            'cancel test rebill' => ['CANCEL-TEST-REBILL', 'cancelled'],
            'auth failure' => ['CUSTOMER_AUTH_FAILURE', 'cancelled'],
        ];
    }

    public function testFromTxnTypeReturnsNullForUnknownType(): void
    {
        self::assertNull(ClickBankInsStatusMapper::fromTxnType('ABANDONED_ORDER'));
    }

    public function testNormalizeFromPayloadPrefersTransactionTypeOverStatusField(): void
    {
        $payload = [
            'transactionType' => 'CANCEL-REBILL',
            'status' => 'approved',
        ];

        self::assertSame('cancelled', ClickBankInsStatusMapper::normalizeFromPayload($payload));
    }

    public function testNormalizeFromPayloadFallsBackToStatusWhenTxnTypeUnknown(): void
    {
        $payload = [
            'transactionType' => 'ABANDONED_ORDER',
            'status' => 'Complete',
        ];

        self::assertSame('complete', ClickBankInsStatusMapper::normalizeFromPayload($payload));
    }

    public function testNormalizeFromPayloadReturnsPendingWhenNothingMatches(): void
    {
        self::assertSame('pending', ClickBankInsStatusMapper::normalizeFromPayload([]));
    }

    public function testExtractTxnTypeNormalizesCase(): void
    {
        self::assertSame('RFND', ClickBankInsStatusMapper::extractTxnType(['transactionType' => 'rfnd']));
    }
}
