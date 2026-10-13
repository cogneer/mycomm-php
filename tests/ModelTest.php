<?php

declare(strict_types=1);

use MyComm\Enums\ChannelStatus;
use MyComm\Enums\ChannelType;
use MyComm\Models\Channel;
use MyComm\Models\Group;
use MyComm\Models\Message;
use MyComm\Models\SendBatchPreview;
use MyComm\Models\SendBatchResponse;
use MyComm\Testing\Examples;

it('reads enums, dates and nested objects', function () {
    $channel = Channel::fromArray(Examples::RESPONSES['channels.get']['body']);

    expect($channel->type)->toBe(ChannelType::AndroidSim)
        ->and($channel->status)->toBe(ChannelStatus::PendingVerification)
        ->and($channel->created_at)->toBeInstanceOf(DateTimeImmutable::class)
        ->and($channel->created_at->format('c'))->toBe('2026-10-01T09:15:02+00:00')
        ->and($channel->health->band)->not->toBeNull()
        ->and($channel->meta())->toBeNull();
});

it('keeps an enum value it does not know as a string', function () {
    $channel = Channel::fromArray(['type' => 'signal', 'status' => 'archived'] + Examples::RESPONSES['channels.get']['body']);

    expect($channel->type)->toBe('signal')
        ->and($channel->status)->toBe('archived');
});

it('keeps fields it does not know in the raw payload', function () {
    $payload = Examples::RESPONSES['groups.show']['body'] + ['colour' => 'teal'];

    $group = Group::fromArray($payload);

    expect($group->raw()['colour'])->toBe('teal')
        ->and($group->toArray())->toBe($payload)
        ->and(json_decode((string) json_encode($group), true))->toBe($payload);
});

it('reads fractional seconds and null timestamps', function () {
    $message = Message::fromArray(['created_at' => '2026-10-01T09:15:02.123456Z', 'sent_at' => null] + Examples::RESPONSES['messages.show']['body']);

    expect($message->created_at->format('Y-m-d\TH:i:s.u'))->toBe('2026-10-01T09:15:02.123456')
        ->and($message->sent_at)->toBeNull();
});

it('tells an accepted message from a dry-run preview in a batch result', function () {
    $message = Examples::RESPONSES['messages.show']['body'];
    $response = SendBatchResponse::fromArray([
        'summary' => ['accepted' => 2, 'rejected' => 1],
        'results' => [
            ['index' => 0, 'status' => 'accepted', 'message' => $message, 'error' => null],
            ['index' => 1, 'status' => 'accepted', 'message' => ['id' => null, 'status' => null, 'to' => '+923001234567', 'segments' => 1], 'error' => null],
            ['index' => 2, 'status' => 'rejected', 'message' => null, 'error' => ['code' => 'INVALID_NUMBER', 'message' => 'Not a phone number.', 'details' => []]],
        ],
    ]);

    expect($response->summary->accepted)->toBe(2)
        ->and($response->results[0]->message)->toBeInstanceOf(Message::class)
        ->and($response->results[1]->message)->toBeInstanceOf(SendBatchPreview::class)
        ->and($response->results[1]->message->segments)->toBe(1)
        ->and($response->results[2]->message)->toBeNull()
        ->and($response->results[2]->error?->code)->toBe('INVALID_NUMBER');
});
