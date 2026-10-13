<?php

declare(strict_types=1);

namespace MyComm;

use MyComm\Http\Connection;
use MyComm\Http\Transport;
use MyComm\Http\TransportFactory;
use MyComm\Resources\Concerns\ProvidesResources;
use Psr\Http\Client\ClientInterface;
use SensitiveParameter;

/**
 * The MyComm API.
 *
 *     $mycomm = new MyComm\Client('mc_live_...');
 *     $message = $mycomm->messages->send(to: '+923001234567', body: 'Your code is 4821');
 *
 * Every resource of the API reference is a property (`messages`, `contacts`, `webhookEndpoints`, ...) and
 * every operation a method on it. The partner API lives under `partner`.
 */
final class Client
{
    use ProvidesResources;

    public const DEFAULT_BASE_URL = 'https://mycomm.cogneer.tech';

    private readonly Transport $transport;

    private readonly string $baseUrl;

    /**
     * @param  string  $apiKey  An account key (`mc_live_...`) or a partner key (`mc_partner_...`).
     * @param  string  $baseUrl  Origin of the API, without `/v1`.
     * @param  ClientInterface|Transport|null  $httpClient  Your own PSR-18 client. By default Guzzle is used when installed, else the client discovery finds.
     * @param  int  $maxRetries  Retries after a network error, a 429 or a 5xx. POSTs carry an `Idempotency-Key`, so a retry never repeats their effect.
     * @param  float  $timeout  Seconds per attempt; applies to the default HTTP client only.
     * @param  string|null  $accountId  With a partner key: the managed account to act as on `/v1`. See `forAccount()`.
     */
    public function __construct(
        #[SensitiveParameter] private readonly string $apiKey,
        string $baseUrl = self::DEFAULT_BASE_URL,
        ClientInterface|Transport|null $httpClient = null,
        private readonly int $maxRetries = 2,
        private readonly float $timeout = 30.0,
        private readonly ?string $accountId = null,
    ) {
        if (trim($apiKey) === '') {
            throw new Exceptions\MyCommException('The MyComm API key is empty.');
        }

        // The paths carry /v1 and /partner/v1 themselves; forgive a base URL that already ends in one.
        $this->baseUrl = (string) preg_replace('#/(partner/)?v1$#', '', rtrim($baseUrl, '/'));
        $this->transport = $httpClient instanceof Transport ? $httpClient : TransportFactory::make($httpClient, $timeout);
        $this->bootResources(new Connection($this->transport, $apiKey, $this->baseUrl, $accountId, $maxRetries));
    }

    /**
     * A client that acts as one managed account: every `/v1` call carries `MyComm-Account`.
     * For partner keys.
     */
    public function forAccount(string $accountId): self
    {
        return new self($this->apiKey, $this->baseUrl, $this->transport, $this->maxRetries, $this->timeout, $accountId);
    }
}
