<?php

declare(strict_types=1);

use MyComm\Exceptions\WebhookSignatureException;
use MyComm\Models\Message;
use MyComm\Testing\Examples;
use MyComm\Testing\FakeWebhook;
use MyComm\Webhooks\Event;
use MyComm\Webhooks\EventMap;
use MyComm\Webhooks\Events\MessageDelivered;
use MyComm\Webhooks\Events\Ping;
use MyComm\Webhooks\UnknownEvent;
use MyComm\Webhooks\Webhook;

const SECRET = '5f1c8a0e9d3b47c2a6e4f0b1d2c3a4e5f6a7b8c9d0e1f2a3b4c5d6e7f8a9b0c1';

it('accepts the signature format of the API reference', function () {
    // The backend signs `HMAC-SHA256(secret, "<t>.<raw body>")` as lowercase hex.
    $body = '{"id":"01923f88-d4e5-7f6a-8b7c-8d9e0f1a2b3c","type":"ping"}';
    $header = 't=1759310182,v1='.hash_hmac('sha256', '1759310182.'.$body, SECRET);

    Webhook::verify($body, $header, SECRET, now: 1759310182);

    expect(Webhook::sign($body, SECRET, 1759310182))->toBe($header);
});

it('returns the typed event of a verified delivery', function () {
    $webhook = FakeWebhook::make('message.delivered', ['data' => ['message' => ['status' => 'delivered']]], SECRET);

    $event = Webhook::constructEvent($webhook->payload, $webhook->signature, SECRET);

    expect($event)->toBeInstanceOf(MessageDelivered::class)
        ->and($event->type)->toBe('message.delivered')
        ->and($event->id)->toBe(Examples::EVENTS['message.delivered']['id'])
        ->and($event->created_at)->toBeInstanceOf(DateTimeImmutable::class)
        ->and($event->channel?->type)->not->toBeNull()
        ->and($event->data->message)->toBeInstanceOf(Message::class)
        ->and($event->data->message->status)->toBe('delivered');
});

it('accepts either signature during a secret rotation', function () {
    $body = FakeWebhook::make('ping')->payload;
    $t = 1759310182;
    $header = "t={$t},v1=".hash_hmac('sha256', "{$t}.{$body}", 'new-secret').',v1='.hash_hmac('sha256', "{$t}.{$body}", 'old-secret');

    Webhook::verify($body, $header, 'old-secret', now: $t);
    Webhook::verify($body, $header, 'new-secret', now: $t);
    Webhook::verify($body, "t={$t},v1=".hash_hmac('sha256', "{$t}.{$body}", 'old-secret'), ['new-secret', 'old-secret'], now: $t);

    expect(fn () => Webhook::verify($body, $header, 'another-secret', now: $t))->toThrow(WebhookSignatureException::class);
});

it('rejects a body that was changed after signing', function () {
    $webhook = FakeWebhook::make('ping', secret: SECRET);

    expect(fn () => Webhook::constructEvent($webhook->payload.' ', $webhook->signature, SECRET))
        ->toThrow(WebhookSignatureException::class, 'No signature of the webhook matches the secret.');
});

it('rejects a timestamp outside the tolerance in either direction', function (int $offset, bool $accepted) {
    $webhook = FakeWebhook::make('ping', secret: SECRET, timestamp: 1_759_310_182);
    $verify = fn () => Webhook::verify($webhook->payload, $webhook->signature, SECRET, now: 1_759_310_182 + $offset);

    $accepted ? expect($verify)->not->toThrow(WebhookSignatureException::class) : expect($verify)->toThrow(WebhookSignatureException::class, 'outside the tolerance');
})->with([[300, true], [-300, true], [301, false], [-301, false]]);

it('rejects a missing or malformed signature header', function (?string $header) {
    expect(fn () => Webhook::verify('{}', $header, SECRET))->toThrow(WebhookSignatureException::class, 'missing or malformed');
})->with([null, '', 'v1=abc', 't=1759310182', 't=abc,v1=abc', 'garbage']);

it('rejects a verified body that is not an event', function () {
    $body = '["not","an","event"]';

    expect(fn () => Webhook::constructEvent($body, Webhook::sign($body, SECRET), SECRET))->toThrow(WebhookSignatureException::class, 'not a MyComm event');
});

it('returns an unknown event for a type added to the API later', function () {
    $body = (string) json_encode(['type' => 'campaign.finished', 'data' => ['campaign_id' => 'c-1']] + Examples::EVENTS['ping']);

    $event = Webhook::constructEvent($body, Webhook::sign($body, SECRET), SECRET);

    expect($event)->toBeInstanceOf(UnknownEvent::class)
        ->and($event->type)->toBe('campaign.finished')
        ->and($event->data)->toBe(['campaign_id' => 'c-1']);
});

it('reads every event of the reference into its class', function (string $type) {
    $event = Webhook::parse((string) json_encode(Examples::EVENTS[$type]));

    expect($event)->toBeInstanceOf(EventMap::EVENTS[$type])
        ->and($event)->toBeInstanceOf(Event::class)
        ->and($event->type)->toBe($type)
        ->and($event::TYPE)->toBe($type)
        ->and($event->raw())->toBe(Examples::EVENTS[$type]);
})->with(array_keys(Examples::EVENTS));

it('reads the sparsest payload of every event', function (string $type) {
    $minimal = (require __DIR__.'/Generated/minimal.php')['events'][$type];

    $event = Webhook::parse((string) json_encode($minimal));

    expect($event)->toBeInstanceOf(EventMap::EVENTS[$type])
        ->and($event->inbox_id)->toBeNull()
        ->and($event->channel)->toBeNull();
})->with(array_keys(Examples::EVENTS));

it('knows the ping the test action sends', function () {
    expect(Webhook::parse(FakeWebhook::make('ping')->payload))->toBeInstanceOf(Ping::class)
        ->and(EventMap::EVENTS)->toHaveCount(38);
});
