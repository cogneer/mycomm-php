<?php

declare(strict_types=1);

namespace MyComm\Testing;

use MyComm\Exceptions\ConnectionException;
use MyComm\Http\Response;

/**
 * An answer the fake gives instead of the API.
 */
final class FakeResponse
{
    private const REQUEST_ID = '01923f80-5c6a-7b10-9c21-0d3e4f5a6b7c';

    /**
     * @param  array<string, mixed>|null  $body
     * @param  array<string, string>  $headers
     * @param  array<string, mixed>|null  $overrides  Set for an example answer: merged over the operation's example.
     */
    private function __construct(
        private readonly ?int $status,
        private readonly ?array $body = null,
        private readonly array $headers = [],
        private readonly ?array $overrides = null,
        private readonly ?string $connectionError = null,
    ) {}

    /**
     * The realistic answer the API reference documents for the operation, with your values on top.
     *
     *     FakeResponse::example(['status' => 'delivered'])
     *
     * @param  array<string, mixed>  $overrides
     */
    public static function example(array $overrides = [], ?int $status = null): self
    {
        return new self($status, overrides: $overrides);
    }

    /**
     * @param  array<string, mixed>  $body
     * @param  array<string, string>  $headers
     */
    public static function json(array $body, int $status = 200, array $headers = []): self
    {
        return new self($status, $body, $headers);
    }

    /**
     * One page of a list. Items are full payloads, or partial ones when the code under test reads `raw()` only.
     *
     * @param  list<array<string, mixed>>  $items
     */
    public static function page(array $items, ?string $nextCursor = null): self
    {
        return new self(200, ['data' => $items, 'next_cursor' => $nextCursor]);
    }

    public static function noContent(): self
    {
        return new self(204);
    }

    /**
     * An error answer in the API's envelope; the client throws the matching exception.
     *
     * @param  array<string, mixed>  $details
     * @param  array<string, string>  $headers  For example `['Retry-After' => '2']`.
     */
    public static function error(int $status, string $code, string $message = 'The request failed.', array $details = [], array $headers = []): self
    {
        return new self($status, ['error' => [
            'code' => $code,
            'message' => $message,
            'details' => $details,
            'request_id' => self::REQUEST_ID,
        ]], $headers);
    }

    /**
     * A `422 VALIDATION_FAILED`.
     *
     * @param  array<string, string>  $fields  Field path => message.
     */
    public static function validationError(array $fields): self
    {
        $violations = [];
        foreach ($fields as $path => $message) {
            $violations[$path] = [['code' => 'INVALID', 'message' => $message]];
        }

        return self::error(422, 'VALIDATION_FAILED', 'The request has invalid fields.', ['fields' => $violations]);
    }

    /**
     * No answer at all: the client throws a `ConnectionException`.
     */
    public static function connectionError(string $message = 'Connection refused'): self
    {
        return new self(null, connectionError: $message);
    }

    /**
     * @internal
     */
    public function resolve(string $operation): Response
    {
        if ($this->connectionError !== null) {
            throw new ConnectionException('Could not reach MyComm: '.$this->connectionError);
        }

        $status = $this->status;
        $body = $this->body;
        if ($this->overrides !== null) {
            $example = Examples::RESPONSES[$operation] ?? ['status' => 200, 'body' => []];
            $status ??= $example['status'];
            $body = $example['body'] === null && $this->overrides === [] ? null : array_replace_recursive($example['body'] ?? [], $this->overrides);
        }

        return new Response(
            $status ?? 200,
            $this->headers + [
                'Content-Type' => 'application/json',
                'MyComm-Request-Id' => self::REQUEST_ID,
                'RateLimit-Limit' => '600',
                'RateLimit-Remaining' => '599',
                'RateLimit-Reset' => '60',
            ],
            $body === null ? '' : (string) json_encode($body, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRESERVE_ZERO_FRACTION),
        );
    }
}
