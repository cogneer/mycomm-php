<?php

declare(strict_types=1);

namespace MyComm\Testing;

use MyComm\Exceptions\MyCommException;
use MyComm\Webhooks\Webhook;

/**
 * A signed webhook request as MyComm would send it, for testing your receiver.
 *
 *     $webhook = FakeWebhook::make('message.delivered', secret: $secret);
 *     $this->call('POST', '/webhooks/mycomm', content: $webhook->payload, server: ['HTTP_MYCOMM_SIGNATURE' => $webhook->signature]);
 */
final class FakeWebhook
{
    private function __construct(
        public readonly string $payload,
        public readonly string $signature,
    ) {}

    /**
     * @param  string  $type  Event type, for example `message.delivered`.
     * @param  array<string, mixed>  $overrides  Laid over the event's example payload.
     * @param  int|null  $timestamp  Unix time of the signature; now when null.
     */
    public static function make(string $type, array $overrides = [], string $secret = 'whsec_fake', ?int $timestamp = null): self
    {
        $example = Examples::EVENTS[$type] ?? throw new MyCommException("No webhook event is named {$type}.");
        $payload = (string) json_encode(array_replace_recursive($example, $overrides), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRESERVE_ZERO_FRACTION);

        return new self($payload, Webhook::sign($payload, $secret, $timestamp));
    }

    /**
     * The request headers of the delivery.
     *
     * @return array<string, string>
     */
    public function headers(): array
    {
        return ['Content-Type' => 'application/json', Webhook::SIGNATURE_HEADER => $this->signature];
    }
}
