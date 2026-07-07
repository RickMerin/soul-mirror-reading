<?php

declare(strict_types=1);

namespace App\Tests;

use App\Logging\ClickBankInsLogger;
use PHPUnit\Framework\TestCase;

final class ClickBankInsLoggerTest extends TestCase
{
    private string $tmpdir = '';

    protected function setUp(): void
    {
        $this->tmpdir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'cb-ins-log-' . bin2hex(random_bytes(4));
        mkdir($this->tmpdir, 0775, true);
        putenv('SOUL_MIRROR_LOG_PATH=' . $this->tmpdir . DIRECTORY_SEPARATOR . 'pipeline.log');
    }

    protected function tearDown(): void
    {
        putenv('SOUL_MIRROR_LOG_PATH');
        if ($this->tmpdir !== '' && is_dir($this->tmpdir)) {
            $log = $this->tmpdir . DIRECTORY_SEPARATOR . 'clickbank-ins.log';
            if (is_file($log)) {
                unlink($log);
            }
            rmdir($this->tmpdir);
        }
    }

    public function testWritesReceivedParsedAndProcessedLines(): void
    {
        $logger = new ClickBankInsLogger($this->tmpdir);

        $logger->logReceived(512);
        $logger->logParsed('CANCEL-REBILL', 'R1', 'cancelled', 1, ['ic-1']);
        $logger->logProcessed(
            'CANCEL-REBILL',
            'R1',
            'cancelled',
            42,
            17,
            1,
            'soft_cancel',
            false,
            '2026-08-05 03:00:00',
            ['ic-1'],
        );

        $path = $this->tmpdir . DIRECTORY_SEPARATOR . 'clickbank-ins.log';
        self::assertFileExists($path);

        $lines = array_values(array_filter(explode("\n", (string) file_get_contents($path))));
        self::assertCount(3, $lines);

        $received = json_decode($lines[0], true, 512, JSON_THROW_ON_ERROR);
        self::assertSame('received', $received['type']);
        self::assertSame(512, $received['bytes']);

        $parsed = json_decode($lines[1], true, 512, JSON_THROW_ON_ERROR);
        self::assertSame('parsed', $parsed['type']);
        self::assertSame('CANCEL-REBILL', $parsed['transactionType']);
        self::assertSame(['ic-1'], $parsed['itemSkus']);

        $event = json_decode($lines[2], true, 512, JSON_THROW_ON_ERROR);
        self::assertSame('event', $event['type']);
        self::assertSame(42, $event['leadId']);
        self::assertSame('soft_cancel', $event['revocationAction']);
        self::assertFalse($event['buyerRemoved']);
    }
}
