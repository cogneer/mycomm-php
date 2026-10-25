<?php

declare(strict_types=1);

namespace MyComm\Exceptions;

use MyComm\Enums\ErrorCode;
use MyComm\Http\Response;

/**
 * The API answered with an error. Branch on `code()` or `errorCode` (stable); `getMessage()` is the English text of the
 * server, written to say what happened and what to do, and may change. `summary()` puts everything you need for a log
 * line or a support request in one string (also what `echo $e` prints), and `docUrl()` is the page that explains the code.
 */
class ApiException extends MyCommException
{
    /**
     * @param  int  $status  HTTP status.
     * @param  string  $errorCode  Error code of the API, for example `RECIPIENT_OPTED_OUT`; empty when the answer carried none.
     * @param  array<string, mixed>  $details  Code-specific details.
     * @param  string|null  $requestId  Quote it to support.
     * @param  string|null  $docUrl  The page of the error reference that explains the code (`error.doc_url`).
     */
    final public function __construct(
        string $message,
        public readonly int $status,
        public readonly string $errorCode = '',
        public readonly array $details = [],
        public readonly ?string $requestId = null,
        public readonly ?Response $response = null,
        public readonly ?string $docUrl = null,
    ) {
        parent::__construct($message, $status);
    }

    public function is(ErrorCode|string $code): bool
    {
        return $this->errorCode === ($code instanceof ErrorCode ? $code->value : $code);
    }

    /**
     * Whether the error is any of these codes.
     */
    public function isAnyOf(ErrorCode|string ...$codes): bool
    {
        foreach ($codes as $code) {
            if ($this->is($code)) {
                return true;
            }
        }

        return false;
    }

    /**
     * The code as an enum case, or null for a code this SDK version does not know (`errorCode` has the text).
     */
    public function code(): ?ErrorCode
    {
        return ErrorCode::tryFrom($this->errorCode);
    }

    /**
     * The page of the error reference that explains this code and what to do about it.
     */
    public function docUrl(): ?string
    {
        return $this->docUrl;
    }

    /**
     * One line with the code, status, message, details and request id, for logs and support requests.
     */
    public function summary(): string
    {
        $parts = [($this->errorCode !== '' ? $this->errorCode : 'ERROR').' ('.$this->status.'): '.$this->getMessage()];
        $details = $this->summaryDetails();
        if ($details !== '') {
            $parts[] = 'details: '.$details;
        }
        if ($this->requestId !== null) {
            $parts[] = 'request_id: '.$this->requestId;
        }
        if ($this->docUrl !== null) {
            $parts[] = 'see: '.$this->docUrl;
        }

        return implode('  ', $parts);
    }

    public function __toString(): string
    {
        return static::class.': '.$this->summary();
    }

    /** The details worth printing; a validation error lists its fields instead. */
    protected function summaryDetails(): string
    {
        return $this->details === [] ? '' : (string) json_encode($this->details, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
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
            $code === 'INVALID_HEADER' => InvalidHeaderException::class,
            $response->status === 415 => UnsupportedMediaTypeException::class,
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

        return new $class($message, $response->status, $code, is_array($error['details'] ?? null) ? $error['details'] : [], $requestId, $response, is_string($error['doc_url'] ?? null) ? $error['doc_url'] : null);
    }
}
