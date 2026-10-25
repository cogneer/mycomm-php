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
     * @param  bool  $validate  Check the arguments of each call against the API's documented rules before sending, and throw `InvalidArgumentException` when one breaks a rule. Turn off only if the API has relaxed a rule this SDK version does not know yet.
     */
    public function __construct(
        #[SensitiveParameter] private readonly string $apiKey,
        string $baseUrl = self::DEFAULT_BASE_URL,
        ClientInterface|Transport|null $httpClient = null,
        private readonly int $maxRetries = 2,
        private readonly float $timeout = 30.0,
        private readonly ?string $accountId = null,
        private readonly bool $validate = true,
    ) {
        if (trim($apiKey) === '') {
            throw new Exceptions\MyCommException('The MyComm API key is empty.');
        }

        // The paths carry /v1 and /partner/v1 themselves; forgive a base URL that already ends in one.
        $this->baseUrl = (string) preg_replace('#/(partner/)?v1$#', '', rtrim($baseUrl, '/'));
        $this->transport = $httpClient instanceof Transport ? $httpClient : TransportFactory::make($httpClient, $timeout);
        $this->bootResources(new Connection($this->transport, $apiKey, $this->baseUrl, $accountId, $maxRetries, validate: $validate));
    }

    /**
     * Checks that the key works and, when you give a number to test with, that a message to it would be accepted.
     * Nothing is sent: the second check is a dry run (`dry_run: true`), which runs the same authentication, validation,
     * recipient, content and safety checks as a real send and stores nothing. It does not check that the phone is online or
     * that the carrier delivers; see the guide "Verify your integration".
     *
     * Never throws for a problem with the key or the request: each problem is a failed check in the result, with the API's
     * own message. It does not retry, so a failure is reported at once.
     *
     * @param  string|null  $to  A number you control, to dry-run a send to.
     * @param  string|null  $inboxId  The inbox to send from; leave out when the key has only one.
     */
    public function verify(?string $to = null, ?string $inboxId = null): VerifyResult
    {
        $once = new RequestOptions(maxRetries: 0);
        $checks = [];
        $account = null;

        try {
            $account = $this->account->get(requestOptions: $once);
            $checks[] = ['name' => 'key', 'ok' => true, 'message' => 'The key works for account '.$account->id.'.'];
        } catch (Exceptions\MyCommException $e) {
            $checks[] = ['name' => 'key', 'ok' => false, 'message' => $e->getMessage()];

            return new VerifyResult($checks);
        }

        $holdReason = null;
        if ($to !== null) {
            try {
                $result = $this->messages->send(
                    inbox_id: $inboxId,
                    to: $to,
                    body: 'MyComm verify',
                    category: 'transactional',
                    dry_run: true,
                    requestOptions: $once,
                );
                $holdReason = $result instanceof Models\SendMessageDryRunResponse ? ($result->hold_reason instanceof \BackedEnum ? $result->hold_reason->value : $result->hold_reason) : null;
                $checks[] = ['name' => 'send', 'ok' => true, 'message' => 'A message to '.$to.' would be accepted'.($holdReason !== null ? ' but held ('.$holdReason.')' : '').'.'];
            } catch (Exceptions\MyCommException $e) {
                $checks[] = ['name' => 'send', 'ok' => false, 'message' => $e->getMessage()];
            }
        }

        return new VerifyResult($checks, $account, $holdReason);
    }

    /**
     * A client that acts as one managed account: every `/v1` call carries `MyComm-Account`.
     * For partner keys.
     */
    public function forAccount(string $accountId): self
    {
        return new self($this->apiKey, $this->baseUrl, $this->transport, $this->maxRetries, $this->timeout, $accountId, $this->validate);
    }
}
