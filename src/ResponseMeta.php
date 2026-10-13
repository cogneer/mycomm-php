<?php

declare(strict_types=1);

namespace MyComm;

use MyComm\Http\Response;

/**
 * What the API said about a call besides its body: `$message->meta()`, `$page->meta()`.
 */
final class ResponseMeta
{
    /**
     * @param  string|null  $requestId  `MyComm-Request-Id`: quote it to support.
     * @param  int|null  $rateLimitReset  Seconds until the rate-limit window resets.
     * @param  bool  $idempotentReplayed  True when this is the stored answer of an earlier call with the same key.
     * @param  string|null  $deprecation  `Deprecation` header of a deprecated endpoint, `@<unix time>`.
     * @param  string|null  $sunset  HTTP-date after which a deprecated endpoint stops working.
     * @param  array<string, string>  $headers  All response headers, names in lower case.
     */
    public function __construct(
        public readonly int $status,
        public readonly ?string $requestId = null,
        public readonly ?int $rateLimitLimit = null,
        public readonly ?int $rateLimitRemaining = null,
        public readonly ?int $rateLimitReset = null,
        public readonly bool $idempotentReplayed = false,
        public readonly ?string $deprecation = null,
        public readonly ?string $sunset = null,
        public readonly array $headers = [],
    ) {}

    public static function fromResponse(Response $response): self
    {
        $int = static function (string $name) use ($response): ?int {
            $value = $response->header($name);

            return $value !== null && is_numeric($value) ? (int) $value : null;
        };

        return new self(
            status: $response->status,
            requestId: $response->header('MyComm-Request-Id'),
            rateLimitLimit: $int('RateLimit-Limit'),
            rateLimitRemaining: $int('RateLimit-Remaining'),
            rateLimitReset: $int('RateLimit-Reset'),
            idempotentReplayed: $response->header('Idempotent-Replayed') === 'true',
            deprecation: $response->header('Deprecation'),
            sunset: $response->header('Sunset'),
            headers: $response->headers,
        );
    }
}
