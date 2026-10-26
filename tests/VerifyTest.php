<?php

declare(strict_types=1);

use MyComm\Testing\FakeResponse;
use MyComm\Testing\MyCommFake;

it('passes when the key works and a dry run is accepted, and sends nothing', function () {
    $fake = new MyCommFake;
    $fake->respond('messages.send', FakeResponse::example(['dry_run' => true, 'id' => null, 'status' => null, 'hold_reason' => 'device_offline'], 200));

    $result = $fake->client()->verify(to: '+923001234567');

    expect($result->ok())->toBeTrue()
        ->and(array_column($result->checks, 'name'))->toBe(['key', 'send']);
    $fake->assertCalled('messages.send', fn ($request) => ($request->json['dry_run'] ?? null) === true);
});

it('reports a refused key as a failed check instead of throwing', function () {
    $fake = new MyCommFake;
    $fake->respond('account.get', FakeResponse::error(401, 'UNAUTHENTICATED', 'The API key is not valid.'));

    $result = $fake->client()->verify(to: '+923001234567');

    expect($result->ok())->toBeFalse()
        ->and($result->problems())->toBe(['The API key is not valid.'])
        ->and($result->account)->toBeNull();
    $fake->assertNotCalled('messages.send');
});

it('reports the reason a send would be refused', function () {
    $fake = new MyCommFake;
    $fake->respond('messages.send', FakeResponse::error(422, 'RECIPIENT_IS_CHANNEL', 'The recipient is the number this message would be sent from.'));

    $result = $fake->client()->verify(to: '+923001234567');

    expect($result->ok())->toBeFalse()->and($result->problems())->toBe(['The recipient is the number this message would be sent from.']);
});

it('checks only the key when no number is given', function () {
    $fake = new MyCommFake;

    expect($fake->client()->verify()->checks)->toHaveCount(1);
    $fake->assertNotCalled('messages.send');
});
