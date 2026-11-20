<?php

declare(strict_types=1);

namespace MyComm\Exceptions;

use MyComm\Http\RetryAfter;

/**
 * 5xx: the API failed. Repeating a POST with the same `Idempotency-Key` is safe.
 */
final class ServerException extends ApiException
{
    /**
     * Seconds the API asks to wait, from `Retry-After` (a `503`, such as `CHANNEL_SERVER_UNAVAILABLE`, may carry it).
     */
    public function retryAfter(): ?int
    {
        return RetryAfter::seconds($this->response?->header('Retry-After'));
    }
}
