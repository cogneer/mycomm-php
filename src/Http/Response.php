<?php

declare(strict_types=1);

namespace MyComm\Http;

use MyComm\Exceptions\UnexpectedResponseException;
use MyComm\ResponseMeta;

final class Response
{
    /** @var array<string, string> */
    public readonly array $headers;

    private ?ResponseMeta $meta = null;

    /**
     * @param  array<string, string>  $headers
     */
    public function __construct(
        public readonly int $status,
        array $headers = [],
        public readonly string $body = '',
    ) {
        $this->headers = array_change_key_case($headers, CASE_LOWER);
    }

    public function header(string $name): ?string
    {
        return $this->headers[strtolower($name)] ?? null;
    }

    /**
     * The decoded JSON object of the body; an empty array for an empty body.
     *
     * @return array<string, mixed>
     */
    public function json(): array
    {
        if ($this->body === '') {
            return [];
        }

        $decoded = json_decode($this->body, true);
        if (! is_array($decoded)) {
            throw new UnexpectedResponseException("The API answered {$this->status} with a body that is not a JSON object.", $this);
        }

        return $decoded;
    }

    public function meta(): ResponseMeta
    {
        return $this->meta ??= ResponseMeta::fromResponse($this);
    }
}
