<?php

declare(strict_types=1);

use MyComm\FileUpload;
use MyComm\Model;
use MyComm\Page;
use MyComm\Testing\Examples;
use MyComm\Testing\FakeResponse;
use MyComm\Testing\MyCommFake;

/*
 * Every method of every resource, called with example arguments against the example answers the generator
 * built from the API reference. A row per operation, so a spec change that breaks a method names it.
 */

$operations = require __DIR__.'/Generated/operations.php';
$minimal = require __DIR__.'/Generated/minimal.php';

/**
 * @param  array<string, mixed>  $row
 */
function callOperation(MyCommFake $fake, array $row): mixed
{
    $resource = $fake->client();
    foreach ($row['resource'] as $segment) {
        $resource = $resource->{$segment};
    }
    $arguments = array_map(
        static fn (mixed $value): mixed => $value === '@file' ? FileUpload::fromString("phone\n+923001234567\n", 'contacts.csv', 'text/csv') : $value,
        $row['arguments'],
    );

    return $resource->{$row['method']}(...$arguments);
}

$byName = [];
foreach ($operations as $row) {
    $byName[$row['operation'].($row['format'] === 'multipart' ? ' (multipart)' : '')] = [$row];
}

it('calls the documented endpoint and reads the documented answer', function (array $row) {
    $fake = new MyCommFake;

    $result = callOperation($fake, $row);

    $calls = $fake->calls($row['operation']);
    expect($calls)->toHaveCount(1)
        ->and($calls[0]->method)->toBe($row['http'])
        ->and($calls[0]->path)->toBe($row['path'])
        ->and($calls[0]->header('Authorization'))->toBe('Bearer mc_live_fake');

    if (in_array($row['http'], ['POST', 'PUT', 'PATCH'], true)) {
        // The API refuses a write without a JSON object or a multipart body.
        $row['format'] === 'multipart'
            ? expect($calls[0]->header('Content-Type'))->toStartWith('multipart/form-data; boundary=')
            : expect(json_decode((string) $calls[0]->body))->toBeObject();
    } else {
        expect($calls[0]->body)->toBeNull();
    }
    expect($calls[0]->header('Idempotency-Key') !== null)->toBe($row['http'] === 'POST');

    $example = Examples::RESPONSES[$row['operation']] ?? null;
    match ($row['returns']) {
        'void', 'null' => expect($result)->toBeNull(),
        'json' => expect($result)->toBeArray(),
        'page' => expect($result)->toBeInstanceOf(Page::class)
            ->and($result->data)->toHaveCount(count($example['body']['data']))
            ->and($row['item'] === null || $result->data[0] instanceof $row['item'])->toBeTrue()
            ->and($result->meta()?->requestId)->not->toBeNull(),
        default => expect($result)->toBeInstanceOf($row['returns'])
            ->and($result->raw())->toBe($example['body'])
            ->and($result->meta()?->status)->toBe($example['status']),
    };
})->with($byName);

it('reads the sparsest answer the reference allows', function (array $case) use ($operations) {
    $rows = array_values(array_filter($operations, static fn (array $row): bool => $row['operation'] === $case['operation']));
    $fake = new MyCommFake;
    $fake->respond($case['operation'], FakeResponse::json($case['body'], $case['status']));

    foreach ($rows as $row) {
        $result = callOperation($fake, $row);

        expect($result)->not->toBeNull();
        if ($result instanceof Model) {
            expect($result->raw())->toBe($case['body']);
        }
    }
})->with(array_combine(
    array_map(static fn (array $case): string => $case['operation'].' '.$case['status'], $minimal['responses']),
    array_map(static fn (array $case): array => [$case], $minimal['responses']),
));

it('covers every operation of both specs', function () use ($operations) {
    expect(count($operations))->toBeGreaterThan(120)
        ->and(array_filter($operations, static fn (array $row): bool => $row['resource'][0] === 'partner'))->toHaveCount(11);
});
