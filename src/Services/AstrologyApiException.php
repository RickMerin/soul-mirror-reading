<?php

declare(strict_types=1);

namespace App\Services;

use RuntimeException;

/**
 * Raised when tarot_predictions cannot be retrieved (maps to HTTP 502 for clients).
 */
final class AstrologyApiException extends RuntimeException
{
}
