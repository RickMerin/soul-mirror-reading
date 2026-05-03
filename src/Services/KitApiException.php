<?php

declare(strict_types=1);

namespace App\Services;

use RuntimeException;

/**
 * Thrown when Kit (ConvertKit) API v4 returns a non-success HTTP status.
 */
final class KitApiException extends RuntimeException
{
    public function __construct(
        public readonly int $statusCode,
        string $message,
    ) {
        parent::__construct($message);
    }
}
