<?php

declare(strict_types=1);

namespace MyComm\Http;

use Closure;
use MyComm\Exceptions\ApiException;
use MyComm\Exceptions\ConnectionException;
use MyComm\FileUpload;
use MyComm\RequestOptions;
use MyComm\Version;
use SensitiveParameter;

/**
 * Everything the resources share: authentication, headers, encoding, retries and turning error answers
 * into exceptions.
 *
 * @internal
 */
final class Connection
{
    /** A repeat while the first request with the same key is still running: the API asks to wait a second. */
    private const IN_PROGRESS = 'IDEMPOTENCY_IN_PROGRESS';

    private const MAX_DELAY = 60.0;

    /** @var Closure(float): void */
    private readonly Closure $sleep;

    /**
     * @param  (Closure(float): void)|null  $sleep  Waits the given seconds between retries.
     */
    public function __construct(
        private readonly Transport $transport,
        #[SensitiveParameter] private readonly string $apiKey,
        private readonly string $baseUrl,
        private readonly ?string $accountId = null,
        private readonly int $maxRetries = 2,
        ?Closure $sleep = null,
    ) {
        $this->sleep = $sleep ?? static function (float $seconds): void {
            usleep((int) round($seconds * 1_000_000));
        };
    }

    /**
     * @param  array<string, mixed>  $query
     * @param  array<string, mixed>|null  $body  JSON body.
     * @param  array<string, mixed>|null  $multipart  Fields of a multipart body, instead of `$body`.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function request(
        string $operation,
        string $method,
        string $path,
        array $query = [],
        ?array $body = null,
        ?array $multipart = null,
        ?RequestOptions $options = null,
    ): Response {
        $request = $this->build($operation, $method, $path, $query, $body, $multipart, $options);
        $retries = max(0, $options->maxRetries ?? $this->maxRetries);

        for ($attempt = 0; ; $attempt++) {
            try {
                $response = $this->transport->send($request);
            } catch (ConnectionException $e) {
                if ($attempt >= $retries || ! self::repeatable($request)) {
                    throw $e;
                }
                ($this->sleep)(self::backoff($attempt));

                continue;
            }

            if ($response->status >= 200 && $response->status < 300) {
                return $response;
            }

            $error = ApiException::fromResponse($response);
            $delay = $attempt < $retries ? self::retryDelay($request, $response, $error, $attempt) : null;
            if ($delay === null) {
                throw $error;
            }
            ($this->sleep)($delay);
        }
    }

    /**
     * @param  array<string, mixed>  $query
     * @param  array<string, mixed>|null  $body
     * @param  array<string, mixed>|null  $multipart
     */
    private function build(string $operation, string $method, string $path, array $query, ?array $body, ?array $multipart, ?RequestOptions $options): Request
    {
        $query = Params::query($query);
        $headers = [
            'Authorization' => 'Bearer '.$this->apiKey,
            'Accept' => 'application/json',
            'User-Agent' => 'mycomm-php/'.Version::SDK.' php/'.PHP_VERSION,
        ];
        // Acting as a managed account applies to /v1 only; /partner/v1 is the partner's own context.
        if ($this->accountId !== null && str_starts_with($path, '/v1/')) {
            $headers['MyComm-Account'] = $this->accountId;
        }

        $json = null;
        $parts = null;
        $encoded = null;
        if ($multipart !== null) {
            $parts = Params::multipart($multipart);
            $boundary = 'mycomm-'.bin2hex(random_bytes(16));
            $headers['Content-Type'] = 'multipart/form-data; boundary='.$boundary;
            $encoded = self::encodeMultipart($parts, $boundary);
        } elseif (in_array($method, ['POST', 'PUT', 'PATCH'], true)) {
            // The API requires a JSON object on every write, also when the operation takes no fields.
            $json = $body ?? [];
            $headers['Content-Type'] = 'application/json';
            $encoded = $json === [] ? '{}' : json_encode($json, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRESERVE_ZERO_FRACTION);
        }

        if ($method === 'POST') {
            $headers['Idempotency-Key'] = $options->idempotencyKey ?? self::uuid();
        }
        foreach ($options->headers ?? [] as $name => $value) {
            $headers[$name] = $value;
        }

        $url = $this->baseUrl.$path.($query === [] ? '' : '?'.http_build_query($query, '', '&', PHP_QUERY_RFC3986));

        return new Request($operation, $method, $url, $path, $query, $headers, $json, $parts, $encoded);
    }

    /**
     * Seconds to wait before repeating the request, or null when this answer must not be retried.
     */
    private static function retryDelay(Request $request, Response $response, ApiException $error, int $attempt): ?float
    {
        $retryAfter = $response->header('Retry-After');
        $wait = $retryAfter !== null && is_numeric($retryAfter) ? min((float) $retryAfter, self::MAX_DELAY) : null;

        return match (true) {
            // Rejected before any work started, so safe for every method.
            $response->status === 429, $response->status === 503 => $wait ?? self::backoff($attempt),
            $response->status === 409 && $error->errorCode === self::IN_PROGRESS => $wait ?? 1.0,
            $response->status >= 500 && self::repeatable($request) => $wait ?? self::backoff($attempt),
            default => null,
        };
    }

    /**
     * Whether sending the request twice is harmless: a read, a write that sets a state, or a POST that
     * carries an `Idempotency-Key` (the API then replays the first answer instead of running again).
     */
    private static function repeatable(Request $request): bool
    {
        return $request->method !== 'POST' || $request->header('Idempotency-Key') !== null;
    }

    private static function backoff(int $attempt): float
    {
        $base = min(0.5 * (2 ** $attempt), 8.0);

        return $base * (0.75 + random_int(0, 500) / 1000);
    }

    /**
     * @param  array<string, string|FileUpload>  $parts
     */
    private static function encodeMultipart(array $parts, string $boundary): string
    {
        $body = '';
        foreach ($parts as $name => $part) {
            $body .= "--{$boundary}\r\n";
            if ($part instanceof FileUpload) {
                $filename = str_replace(['"', "\r", "\n"], ['%22', '', ''], $part->filename);
                $body .= "Content-Disposition: form-data; name=\"{$name}\"; filename=\"{$filename}\"\r\n";
                $body .= "Content-Type: {$part->contentType}\r\n\r\n{$part->contents}\r\n";
            } else {
                $body .= "Content-Disposition: form-data; name=\"{$name}\"\r\n\r\n{$part}\r\n";
            }
        }

        return $body."--{$boundary}--\r\n";
    }

    private static function uuid(): string
    {
        $bytes = random_bytes(16);
        $bytes[6] = chr((ord($bytes[6]) & 0x0F) | 0x40);
        $bytes[8] = chr((ord($bytes[8]) & 0x3F) | 0x80);

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($bytes), 4));
    }
}
