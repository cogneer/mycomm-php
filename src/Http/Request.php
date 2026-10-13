<?php

declare(strict_types=1);

namespace MyComm\Http;

use MyComm\FileUpload;

/**
 * A request as the SDK is about to send it. Tests read `operation`, `query` and `json` from the fake.
 */
final class Request
{
    /**
     * @param  string  $operation  Operation id of the API reference, for example `messages.send`.
     * @param  string  $path  Path below the base URL, for example `/v1/messages`.
     * @param  array<string, string>  $query  Query parameters as sent.
     * @param  array<string, string>  $headers
     * @param  array<string, mixed>|null  $json  The JSON body before encoding; null when there is none.
     * @param  array<string, string|FileUpload>|null  $multipart  The fields of a multipart body.
     * @param  string|null  $body  The encoded body.
     */
    public function __construct(
        public readonly string $operation,
        public readonly string $method,
        public readonly string $url,
        public readonly string $path,
        public readonly array $query = [],
        public readonly array $headers = [],
        public readonly ?array $json = null,
        public readonly ?array $multipart = null,
        public readonly ?string $body = null,
    ) {}

    public function header(string $name): ?string
    {
        foreach ($this->headers as $key => $value) {
            if (strcasecmp($key, $name) === 0) {
                return $value;
            }
        }

        return null;
    }
}
