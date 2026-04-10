<?php

declare(strict_types=1);

namespace App\Application;

/**
 * HTTP status + JSON body for the `/api/reading` endpoint.
 */
final readonly class ReadingResult
{
    /**
     * @param array<string, mixed> $json
     */
    public function __construct(
        public int $httpStatus,
        public array $json,
    ) {}
}
