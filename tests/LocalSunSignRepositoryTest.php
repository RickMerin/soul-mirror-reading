<?php

declare(strict_types=1);

namespace App\Tests;

use App\Services\LocalSunSignRepository;
use DateTimeImmutable;
use DateTimeZone;
use PHPUnit\Framework\TestCase;

final class LocalSunSignRepositoryTest extends TestCase
{
    public function testLoadsSignFieldsFromDatedFile(): void
    {
        $fixture = \dirname(__DIR__) . '/data/sun-sign/2026-05-19.json';
        if (!is_readable($fixture)) {
            $this->markTestSkipped('Fixture sun-sign file missing: ' . $fixture);
        }

        $tmpDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'smr_sun_' . uniqid('', true);
        mkdir($tmpDir);
        $date = (new DateTimeImmutable('now', new DateTimeZone('UTC')))->format('Y-m-d');
        copy($fixture, $tmpDir . DIRECTORY_SEPARATOR . $date . '.json');

        $repo = new LocalSunSignRepository($tmpDir, new DateTimeZone('UTC'));
        $fields = $repo->forSign('aries');

        $this->assertNotSame('', trim($fields['personal_life']));
        $this->assertArrayHasKey('luck', $fields);
        $this->assertNotSame('', trim($fields['luck']));

        unlink($tmpDir . DIRECTORY_SEPARATOR . $date . '.json');
        rmdir($tmpDir);
    }
}
