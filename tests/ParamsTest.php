<?php

declare(strict_types=1);

use MyComm\Enums\MessageCategory;
use MyComm\Http\Params;
use MyComm\Inputs\ContactUpsert;
use MyComm\Inputs\SendBatchItem;
use MyComm\Omit;

it('drops omitted fields and keeps explicit nulls', function () {
    expect(Params::body(['name' => 'Ada', 'description' => null, 'email' => Omit::Value]))
        ->toBe(['name' => 'Ada', 'description' => null]);
});

it('converts enums, dates and nested inputs', function () {
    $body = Params::body([
        'category' => MessageCategory::Marketing,
        'send_after' => new DateTimeImmutable('2026-10-01 14:15:02.250', new DateTimeZone('Asia/Karachi')),
        'messages' => [new SendBatchItem(to: '+923001234567', body: 'Hi', category: MessageCategory::Transactional)],
    ]);

    expect($body)->toBe([
        'category' => 'marketing',
        'send_after' => '2026-10-01T09:15:02.250000Z',
        'messages' => [['to' => '+923001234567', 'body' => 'Hi', 'category' => 'transactional']],
    ]);
});

it('sends an empty object as {} and never as []', function () {
    expect(json_encode(Params::body(['metadata' => Params::object([])])))->toBe('{"metadata":{}}')
        ->and(json_encode(Params::body(['metadata' => Params::object(['k' => 'v'])])))->toBe('{"metadata":{"k":"v"}}')
        ->and(Params::object(null))->toBeNull()
        ->and(Params::object(Omit::Value))->toBe(Omit::Value);
});

it('keeps null apart from omitted inside a nested input', function () {
    $contact = new ContactUpsert(identity: ['type' => 'phone', 'address' => '+923001234567'], first_name: 'Ada', email: null);

    expect($contact->toArray())->toBe([
        'identity' => ['type' => 'phone', 'address' => '+923001234567'],
        'first_name' => 'Ada',
        'email' => null,
    ]);
});

it('formats query values', function () {
    expect(Params::query(['a' => true, 'b' => false, 'c' => 5, 'd' => null, 'e' => MessageCategory::Marketing, 'f' => ['x', 'y']]))
        ->toBe(['a' => 'true', 'b' => 'false', 'c' => '5', 'e' => 'marketing', 'f' => 'x,y']);
});
