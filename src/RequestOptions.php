<?php

declare(strict_types=1);

namespace MyComm;

/**
 * Per-call settings, passed as the `requestOptions` argument of any resource method.
 */
final class RequestOptions
{
    /**
     * @param  string|null  $idempotencyKey  Sent as `Idempotency-Key` on a POST. The SDK generates one when this is null.
     * @param  int|null  $maxRetries  Overrides the client's retry count for this call.
     * @param  array<string, string>  $headers  Extra request headers.
     */
    public function __construct(
        public readonly ?string $idempotencyKey = null,
        public readonly ?int $maxRetries = null,
        public readonly array $headers = [],
    ) {}
}
