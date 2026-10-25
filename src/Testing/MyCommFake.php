<?php

declare(strict_types=1);

namespace MyComm\Testing;

use Closure;
use MyComm\Client;
use MyComm\Http\Request;
use PHPUnit\Framework\Assert;

/**
 * A MyComm client that never touches the network, for testing code that uses the SDK.
 *
 *     $fake = new MyCommFake;
 *     $service = new OrderNotifier($fake->client());
 *
 *     $service->notify($order);
 *
 *     $fake->assertCalled('messages.send', fn (Request $request) => $request->json['to'] === '+923001234567');
 *
 * Every operation answers with the realistic example of the API reference until you say otherwise with
 * `respond()` or `respondOnce()`. Operations are named by their id in the API reference: `messages.send`,
 * `contacts.list`, `partner.accounts.provision`.
 */
final class MyCommFake
{
    private readonly FakeTransport $transport;

    private readonly Client $client;

    /**
     * @param  bool  $validate  Whether the client checks arguments before sending, as a real client does. Turn it off to test how your code handles what the server answers to a request the SDK would have refused.
     */
    public function __construct(string $apiKey = 'mc_live_fake', ?string $accountId = null, bool $validate = true)
    {
        $this->transport = new FakeTransport;
        // No retries: an error you stub must surface at once, without the client waiting in between.
        $this->client = new Client($apiKey, httpClient: $this->transport, maxRetries: 0, accountId: $accountId, validate: $validate);
    }

    public function client(): Client
    {
        return $this->client;
    }

    /**
     * Answers every call of an operation, until changed.
     *
     * An array is laid over the operation's example: `respond('messages.send', ['status' => 'failed'])`.
     * A closure receives the request and returns an array or a `FakeResponse`.
     *
     * @param  FakeResponse|array<string, mixed>|Closure(Request): (FakeResponse|array<string, mixed>)  $response
     */
    public function respond(string $operation, FakeResponse|array|Closure $response): self
    {
        $this->transport->stubs[$operation] = is_array($response) ? FakeResponse::example($response) : $response;

        return $this;
    }

    /**
     * Answers the next call of an operation; several of these are used in order, before `respond()`.
     *
     * @param  FakeResponse|array<string, mixed>  $response
     */
    public function respondOnce(string $operation, FakeResponse|array $response): self
    {
        $this->transport->queues[$operation][] = is_array($response) ? FakeResponse::example($response) : $response;

        return $this;
    }

    /**
     * The requests made, optionally of one operation.
     *
     * @return list<Request>
     */
    public function calls(?string $operation = null): array
    {
        return array_values(array_filter(
            $this->transport->requests,
            static fn (Request $request): bool => $operation === null || $request->operation === $operation,
        ));
    }

    /**
     * @param  (Closure(Request): bool)|null  $where  Narrows the calls that count.
     */
    public function assertCalled(string $operation, ?Closure $where = null): self
    {
        $calls = $this->matching($operation, $where);
        self::check($calls !== [], $where === null
            ? "Expected a call of {$operation}, but none was made."
            : "Expected a matching call of {$operation}, but none of its ".count($this->calls($operation)).' call(s) matched.');

        return $this;
    }

    /**
     * @param  (Closure(Request): bool)|null  $where
     */
    public function assertCalledTimes(string $operation, int $times, ?Closure $where = null): self
    {
        $count = count($this->matching($operation, $where));
        self::check($count === $times, "Expected {$times} call(s) of {$operation}, but {$count} were made.");

        return $this;
    }

    /**
     * @param  (Closure(Request): bool)|null  $where
     */
    public function assertNotCalled(string $operation, ?Closure $where = null): self
    {
        $count = count($this->matching($operation, $where));
        self::check($count === 0, "Expected no call of {$operation}, but {$count} were made.");

        return $this;
    }

    public function assertNothingCalled(): self
    {
        $operations = array_unique(array_map(static fn (Request $request): string => $request->operation, $this->transport->requests));
        self::check($operations === [], 'Expected no API call, but these were made: '.implode(', ', $operations).'.');

        return $this;
    }

    /**
     * @param  (Closure(Request): bool)|null  $where
     * @return list<Request>
     */
    private function matching(string $operation, ?Closure $where): array
    {
        $calls = $this->calls($operation);

        return $where === null ? $calls : array_values(array_filter($calls, $where));
    }

    private static function check(bool $condition, string $message): void
    {
        if (class_exists(Assert::class)) {
            Assert::assertTrue($condition, $message);

            return;
        }
        if (! $condition) {
            throw new AssertionFailed($message);
        }
    }
}
