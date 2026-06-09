<?php

declare(strict_types=1);

namespace App\Tests;

use App\Services\ReadingDeliveryTrigger;
use PHPUnit\Framework\TestCase;

final class ReadingDeliveryTriggerTest extends TestCase
{
    public function testShouldDeliverForApprovedMainReadingPurchase(): void
    {
        self::assertTrue(ReadingDeliveryTrigger::shouldDeliverForApprovedPurchase('approved', [
            ['sku' => 'smr-1'],
        ]));
    }

    public function testShouldNotDeliverForRefundedPurchase(): void
    {
        self::assertFalse(ReadingDeliveryTrigger::shouldDeliverForApprovedPurchase('refunded', [
            ['sku' => 'smr-1'],
        ]));
    }

    public function testShouldNotDeliverForRitualOnlyPurchase(): void
    {
        self::assertFalse(ReadingDeliveryTrigger::shouldDeliverForApprovedPurchase('approved', [
            ['sku' => 'srp-1'],
        ]));
    }
}
