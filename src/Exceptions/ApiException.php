<?php

declare(strict_types=1);

namespace MyComm\Exceptions;

use MyComm\Enums\ErrorCode;
use MyComm\Http\Response;

/**
 * The API answered with an error. Branch on `errorCode` (stable); `getMessage()` is English text for people
 * and may change.
 */
class ApiException extends MyCommException
{
    /**
     * @param  int  $status  HTTP status.
     * @param  string  $errorCode  Error code of the API, for example `RECIPIENT_OPTED_OUT`; empty when the answer carried none.
     * @param  array<string, mixed>  $details  Code-specific details.
     * @param  string|null  $requestId  Quote it to support.
     */
    final public function __construct(
        string $message,
        public readonly int $status,
        public readonly string $errorCode = '',
        public readonly array $details = [],
        public readonly ?string $requestId = null,
        public readonly ?Response $response = null,
    ) {
        parent::__construct($message, $status);
    }

    public function is(ErrorCode|string $code): bool
    {
        return $this->errorCode === ($code instanceof ErrorCode ? $code->value : $code);
    }

    public static function fromResponse(Response $response): self
    {
        $error = json_decode($response->body, true);
        $error = is_array($error) && is_array($error['error'] ?? null) ? $error['error'] : [];

        $code = is_string($error['code'] ?? null) ? $error['code'] : '';
        $message = is_string($error['message'] ?? null) && $error['message'] !== ''
            ? $error['message']
            : "The MyComm API answered with HTTP {$response->status}.";
        $requestId = is_string($error['request_id'] ?? null) ? $error['request_id'] : $response->header('MyComm-Request-Id');

        // Unknown codes fall back on the status, as the API's versioning policy asks of clients.
        $class = match (true) {
            $code === 'VALIDATION_FAILED' => ValidationException::class,
            $response->status === 400 => BadRequestException::class,
            $response->status === 401 => AuthenticationException::class,
            $response->status === 403 => PermissionDeniedException::class,
            $response->status === 404 => NotFoundException::class,
            $response->status === 409 => ConflictException::class,
            $response->status === 413 => PayloadTooLargeException::class,
            $response->status === 422 => UnprocessableException::class,
            $response->status === 429 => RateLimitException::class,
            $response->status >= 500 => ServerException::class,
            default => self::class,
        };

        return new $class($message, $response->status, $code, is_array($error['details'] ?? null) ? $error['details'] : [], $requestId, $response);
    }
}
