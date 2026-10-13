<?php

declare(strict_types=1);

use MyComm\Exceptions\ConnectionException;
use MyComm\Exceptions\UnprocessableException;
use MyComm\Exceptions\ValidationException;
use MyComm\Http\Request;
use MyComm\Models\SendMessageResponse;
use MyComm\Testing\FakeResponse;
use MyComm\Testing\FakeWebhook;
use MyComm\Testing\MyCommFake;
use MyComm\Webhooks\Webhook;
use PHPUnit\Framework\AssertionFailedError;

it('answers with the example of the reference by default', function () {
    $fake = new MyCommFake;

    $message = $fake->client()->messages->send(to: '+923001234567', body: 'Hi');

    expect($message)->toBeInstanceOf(SendMessageResponse::class)
        ->and($message->meta()?->status)->toBe(202)
        ->and($message->warnings)->toBeArray();
});

it('lays the given values over the example', function () {
    $fake = new MyCommFake;
    $fake->respond('messages.send', ['status' => 'queued', 'to' => '+923331112222']);

    $message = $fake->client()->messages->send(to: '+923331112222', body: 'Hi');

    expect($message->status)->toBe('queued')
        ->and($message->to)->toBe('+923331112222')
        ->and($message->id)->not->toBe('');
});

it('uses queued answers once, in order, before the standing one', function () {
    $fake = new MyCommFake;
    $fake->respond('messages.show', ['status' => 'delivered'])
        ->respondOnce('messages.show', ['status' => 'queued'])
        ->respondOnce('messages.show', ['status' => 'sent']);

    $statuses = array_map(fn () => $fake->client()->messages->show('m-1')->status, range(1, 4));

    expect($statuses)->toBe(['queued', 'sent', 'delivered', 'delivered']);
});

it('computes an answer from the request', function () {
    $fake = new MyCommFake;
    $fake->respond('messages.send', fn (Request $request) => ['to' => $request->json['to'], 'body' => strtoupper($request->json['body'])]);

    $message = $fake->client()->messages->send(to: '+923001234567', body: 'hi');

    expect($message->to)->toBe('+923001234567')
        ->and($message->body)->toBe('HI');
});

it('throws the exception of a stubbed error at once', function () {
    $fake = new MyCommFake;
    $fake->respond('messages.send', FakeResponse::error(422, 'RECIPIENT_OPTED_OUT', 'The recipient opted out.', ['address' => '+923001234567']));

    try {
        $fake->client()->messages->send(to: '+923001234567', body: 'Hi');
        $this->fail('No exception was thrown.');
    } catch (UnprocessableException $e) {
        expect($e->errorCode)->toBe('RECIPIENT_OPTED_OUT')
            ->and($e->details)->toBe(['address' => '+923001234567']);
    }
    $fake->assertCalledTimes('messages.send', 1);
});

it('stubs validation and network failures', function () {
    $fake = new MyCommFake;
    $fake->respondOnce('contacts.create', FakeResponse::validationError(['identities.0.address' => 'Not a phone number.']))
        ->respondOnce('contacts.create', FakeResponse::connectionError());
    $create = fn () => $fake->client()->contacts->create(identities: [['type' => 'phone', 'address' => 'x']]);

    expect($create)->toThrow(ValidationException::class)
        ->and($create)->toThrow(ConnectionException::class);
});

it('records what was sent', function () {
    $fake = new MyCommFake;
    $client = $fake->client();

    $client->messages->send(to: '+923001234567', body: 'Hi', metadata: []);
    $client->groups->update('g-1', description: null);

    $fake->assertCalled('messages.send')
        ->assertCalled('messages.send', fn (Request $request) => $request->json['to'] === '+923001234567')
        ->assertCalledTimes('groups.update', 1)
        ->assertNotCalled('messages.sendBatch')
        ->assertNotCalled('messages.send', fn (Request $request) => $request->json['to'] === '+10000000000');

    expect($fake->calls())->toHaveCount(2)
        ->and($fake->calls('messages.send')[0]->body)->toBe('{"to":"+923001234567","body":"Hi","metadata":{}}')
        ->and($fake->calls('groups.update')[0]->body)->toBe('{"description":null}');
});

it('fails its assertions with a readable message', function (Closure $assertion, string $message) {
    $fake = new MyCommFake;
    $fake->client()->account->get();

    expect(fn () => $assertion($fake))->toThrow(AssertionFailedError::class, $message);
})->with([
    [fn (MyCommFake $fake) => $fake->assertCalled('messages.send'), 'Expected a call of messages.send, but none was made.'],
    [fn (MyCommFake $fake) => $fake->assertCalled('account.get', fn () => false), 'Expected a matching call of account.get, but none of its 1 call(s) matched.'],
    [fn (MyCommFake $fake) => $fake->assertCalledTimes('account.get', 2), 'Expected 2 call(s) of account.get, but 1 were made.'],
    [fn (MyCommFake $fake) => $fake->assertNotCalled('account.get'), 'Expected no call of account.get, but 1 were made.'],
    [fn (MyCommFake $fake) => $fake->assertNothingCalled(), 'Expected no API call, but these were made: account.get.'],
]);

it('passes assertNothingCalled on an untouched fake', function () {
    (new MyCommFake)->assertNothingCalled();
});

it('builds a signed webhook delivery for a receiver test', function () {
    $webhook = FakeWebhook::make('message.received', ['data' => ['message' => ['body' => 'STOP']]], 'whsec_1');

    $event = Webhook::constructEvent($webhook->payload, $webhook->headers()['MyComm-Signature'], 'whsec_1');

    expect($event->type)->toBe('message.received')
        ->and($event->data->message->body)->toBe('STOP');
});
