<?php

declare(strict_types=1);

namespace MyComm\Exceptions;

/**
 * 429: too many requests. The SDK has already waited and retried before throwing this.
 */
final class RateLimitException extends ApiException
{
    /**
     * Seconds the API asks to wait, from `Retry-After`.
     */
    public function retryAfter(): ?int
    {
        $value = $this->response?->header('Retry-After');

        return $value !== null && is_numeric($value) ? (int) $value : null;
    }
}
