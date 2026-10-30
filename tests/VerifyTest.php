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

it('dry-runs the send from the line it is given', function () {
    $fake = new MyCommFake;
    $fake->respond('messages.send', FakeResponse::example(['dry_run' => true, 'id' => null, 'status' => null], 200));

    $fake->client()->verify(to: '+923001234567', lineId: '0b8f8a52-5f0a-4c8e-9b8e-0d2f3c4a5b6c');

    $fake->assertCalled('messages.send', fn ($request) => ($request->json['line_id'] ?? null) === '0b8f8a52-5f0a-4c8e-9b8e-0d2f3c4a5b6c');
});

test('verifies_a_partner_key_on_a_partner_route_and_dry_runs_through_its_line', function () {
    $fake = new MyCommFake('mc_partner_fake');
    $fake->respond('messages.send', FakeResponse::example(['dry_run' => true, 'id' => null, 'status' => null], 200));
    $fake->respond('account.get', FakeResponse::error(403, 'PARTNER_ROUTE_NOT_ALLOWED', 'A partner key cannot use this route.'));

    $viaArgument = $fake->client()->verify(to: '+923001234567', lineId: 'line-1');
    $viaForLine = $fake->client()->forLine('line-2')->verify(to: '+923001234567');

    expect($viaArgument->ok())->toBeTrue()
        ->and(array_column($viaArgument->checks, 'name'))->toBe(['key', 'send'])
        ->and($viaArgument->account)->toBeNull()
        ->and($viaForLine->ok())->toBeTrue();
    $fake->assertNotCalled('account.get')
        ->assertCalledTimes('partner.lines.list', 2);
    $sends = $fake->calls('messages.send');
    expect($sends[0]->header('MyComm-Line'))->toBe('line-1')
        ->and($sends[0]->json)->not->toHaveKey('line_id')
        ->and($sends[1]->header('MyComm-Line'))->toBe('line-2');
});

test('says_a_partner_key_needs_a_line_to_dry_run_a_send', function () {
    $fake = new MyCommFake('mc_partner_fake');

    $result = $fake->client()->verify(to: '+923001234567');

    expect($result->ok())->toBeFalse()
        ->and($result->checks[0]['ok'])->toBeTrue()
        ->and($result->problems()[0])->toContain('line');
    $fake->assertNotCalled('messages.send');
});

test('reports_a_refused_partner_key', function () {
    $fake = new MyCommFake('mc_partner_fake');
    $fake->respond('partner.lines.list', FakeResponse::error(401, 'UNAUTHENTICATED', 'The API key is not valid.'));

    $result = $fake->client()->verify(to: '+923001234567', lineId: 'line-1');

    expect($result->problems())->toBe(['The API key is not valid.']);
    $fake->assertNotCalled('messages.send');
});
