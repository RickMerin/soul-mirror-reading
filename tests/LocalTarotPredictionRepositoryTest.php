<?php

declare(strict_types=1);

namespace App\Tests;

use App\Services\LocalTarotPredictionRepository;
use PHPUnit\Framework\TestCase;

final class LocalTarotPredictionRepositoryTest extends TestCase
{
    public function testPredictReturnsSlotTextsForFrontendIds(): void
    {
        $path = \dirname(__DIR__) . '/data/tarot-predictions.json';
        $repo = new LocalTarotPredictionRepository($path);

        $result = $repo->predict(1, 2, 3);

        $this->assertNotSame('', trim($result->love));
        $this->assertNotSame('', trim($result->career));
        $this->assertNotSame('', trim($result->finance));
    }
}
