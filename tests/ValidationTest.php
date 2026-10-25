<?php

declare(strict_types=1);

use MyComm\Exceptions\InvalidArgumentException;
use MyComm\Testing\MyCommFake;

/*
 * The checks the SDK runs before it sends: limits and rules written in the OpenAPI document, so a mistake fails here with the
 * field named instead of as a round trip to the server.
 */

function validated(callable $call): InvalidArgumentException
{
    try {
        $call();
    } catch (InvalidArgumentException $e) {
        return $e;
    }
    throw new RuntimeException('Expected the call to be refused before it was sent.');
}

it('refuses a send with no recipient and no content, naming both', function () {
    $fake = new MyCommFake;
    $e = validated(fn () => $fake->client()->messages->send(category: 'transactional'));

    expect(array_column($e->violations, 'rule'))->toBe(['REQUIRED_WITH', 'REQUIRED_WITH'])
        ->and($e->getMessage())->toContain('Give one of to, contact_id')
        ->and($e->getMessage())->toContain('Give at least one of content, body, media_ids, template_id');
    $fake->assertNothingCalled();
});

it('refuses both to and contact_id', function () {
    $e = validated(fn () => (new MyCommFake)->client()->messages->send(to: '+923001234567', contact_id: '01923f80-5c6a-7b10-9c21-0d3e4f5a6b7c', body: 'Hi'));

    expect($e->rule)->toBe('EXCLUSIVE')->and($e->field)->toBe('contact_id');
});

it('requires a category on the bulk lane', function () {
    $e = validated(fn () => (new MyCommFake)->client()->messages->send(to: '+923001234567', body: 'Hi', lane: 'bulk'));

    expect($e->rule)->toBe('REQUIRED_WHEN')->and($e->getMessage())->toBe('category is required when lane is bulk.');
});

it('needs a template for variables', function () {
    $e = validated(fn () => (new MyCommFake)->client()->messages->send(to: '+923001234567', body: 'Hi', variables: ['name' => 'Amina']));

    expect($e->field)->toBe('variables')->and($e->getMessage())->toBe('variables needs template_id.');
});

it('keeps numbers, lengths and formats inside the documented limits', function (string $field, mixed $value, string $rule, string $text) {
    $e = validated(fn () => (new MyCommFake)->client()->messages->send(...[$field => $value, 'to' => '+923001234567', 'body' => 'Hi']));

    expect($e->field)->toBe($field)->and($e->rule)->toBe($rule)->and($e->getMessage())->toContain($text);
})->with([
    'expires_in too small' => ['expires_in', 30, 'OUT_OF_RANGE', 'between 60 and 604800 (got 30)'],
    'expires_in too large' => ['expires_in', 700000, 'OUT_OF_RANGE', 'between 60 and 604800'],
    'reference_id with a space' => ['reference_id', 'order 1', 'INVALID_FORMAT', 'expected format'],
    'reference_id too long' => ['reference_id', str_repeat('x', 101), 'TOO_LONG', 'at most 100 characters (got 101)'],
    'not a uuid' => ['inbox_id', 'inbox-1', 'INVALID_FORMAT', 'must be a UUID'],
    'unknown category' => ['category', 'promo', 'INVALID_ENUM', 'must be one of transactional, marketing'],
    'too many metadata keys' => ['metadata', array_fill_keys(array_map(fn ($i) => "k{$i}", range(1, 51)), 'v'), 'TOO_LONG', 'at most 50 keys'],
    'nested metadata' => ['metadata', ['order' => ['id' => 1]], 'INVALID_TYPE', "'order' does not"],
    'metadata over 2 KB' => ['metadata', ['note' => str_repeat('x', 2100)], 'TOO_LONG', 'at most 2048 bytes as JSON'],
]);

it('reports every problem of a call, not only the first', function () {
    $e = validated(fn () => (new MyCommFake)->client()->messages->send(expires_in: 5, reference_id: 'a b'));

    expect(count($e->violations))->toBeGreaterThanOrEqual(3)->and($e->getMessage())->toContain('more');
});

it('checks the limit of a list call', function () {
    $e = validated(fn () => (new MyCommFake)->client()->messages->list(limit: 500));

    expect($e->field)->toBe('limit')->and($e->rule)->toBe('OUT_OF_RANGE');
});

it('lets a valid call through and sends it', function () {
    $fake = new MyCommFake;
    $fake->client()->messages->send(to: '+923001234567', body: 'Hi', category: 'marketing', expires_in: 600, reference_id: 'order-1', metadata: ['order' => 1]);

    $fake->assertCalledTimes('messages.send', 1);
});

it('can be turned off, leaving the decision to the server', function () {
    $fake = new MyCommFake(validate: false);

    $fake->client()->messages->send(expires_in: 5);

    $fake->assertCalledTimes('messages.send', 1);
});
