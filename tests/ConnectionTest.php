<?php

declare(strict_types=1);

use MyComm\Enums\ErrorCode;
use MyComm\Exceptions\ApiException;
use MyComm\Exceptions\AuthenticationException;
use MyComm\Exceptions\BadRequestException;
use MyComm\Exceptions\ConflictException;
use MyComm\Exceptions\ConnectionException;
use MyComm\Exceptions\NotFoundException;
use MyComm\Exceptions\PayloadTooLargeException;
use MyComm\Exceptions\PermissionDeniedException;
use MyComm\Exceptions\RateLimitException;
use MyComm\Exceptions\ServerException;
use MyComm\Exceptions\UnexpectedResponseException;
use MyComm\Exceptions\UnprocessableException;
use MyComm\Exceptions\ValidationException;
use MyComm\FileUpload;
use MyComm\Http\Response;
use MyComm\RequestOptions;
use MyComm\Version;

it('authenticates and identifies itself', function () {
    [$connection, $transport] = scripted();

    $connection->request('account.get', 'GET', '/v1/account');

    $request = $transport->requests[0];
    expect($request->url)->toBe('https://api.test/v1/account')
        ->and($request->header('Authorization'))->toBe('Bearer mc_live_test')
        ->and($request->header('Accept'))->toBe('application/json')
        ->and($request->header('User-Agent'))->toBe('mycomm-php/'.Version::SDK.' php/'.PHP_VERSION)
        ->and($request->header('Idempotency-Key'))->toBeNull()
        ->and($request->header('Content-Type'))->toBeNull()
        ->and($request->body)->toBeNull();
});

it('sends an empty JSON object on a write without fields', function (string $method) {
    [$connection, $transport] = scripted();

    $connection->request('x', $method, '/v1/x');

    expect($transport->requests[0]->body)->toBe('{}')
        ->and($transport->requests[0]->header('Content-Type'))->toBe('application/json');
})->with(['POST', 'PUT', 'PATCH']);

it('encodes the body as JSON without escaping slashes or unicode', function () {
    [$connection, $transport] = scripted();

    $connection->request('x', 'POST', '/v1/x', body: ['url' => 'https://a.test/b', 'text' => 'سلام', 'n' => 1.0, 'none' => null]);

    expect($transport->requests[0]->body)->toBe('{"url":"https://a.test/b","text":"سلام","n":1.0,"none":null}')
        ->and($transport->requests[0]->json)->toBe(['url' => 'https://a.test/b', 'text' => 'سلام', 'n' => 1.0, 'none' => null]);
});

it('builds the query string and leaves out what was not given', function () {
    [$connection, $transport] = scripted();

    $connection->request('x', 'GET', '/v1/x', query: [
        'status' => 'sent,delivered',
        'unread' => true,
        'blocked' => false,
        'limit' => 50,
        'q' => 'a b&c',
        'created_after' => new DateTimeImmutable('2026-10-01T14:15:02+05:00'),
        'cursor' => null,
    ]);

    expect($transport->requests[0]->url)->toBe('https://api.test/v1/x?status=sent%2Cdelivered&unread=true&blocked=false&limit=50&q=a%20b%26c&created_after=2026-10-01T09%3A15%3A02Z')
        ->and($transport->requests[0]->query)->not->toHaveKey('cursor');
});

it('gives every POST an idempotency key and keeps it across retries', function () {
    [$connection, $transport] = scripted([new Response(500), new Response(202, [], '{}')]);

    $connection->request('messages.send', 'POST', '/v1/messages', body: ['to' => '+923001234567']);

    $keys = array_map(static fn ($request) => $request->header('Idempotency-Key'), $transport->requests);
    expect($keys)->toHaveCount(2)
        ->and($keys[0])->toMatch('/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/')
        ->and($keys[1])->toBe($keys[0]);
});

it('uses the idempotency key and headers of the request options', function () {
    [$connection, $transport] = scripted();

    $connection->request('x', 'POST', '/v1/x', options: new RequestOptions(idempotencyKey: 'order-42', headers: ['X-Trace' => 'abc']));

    expect($transport->requests[0]->header('Idempotency-Key'))->toBe('order-42')
        ->and($transport->requests[0]->header('X-Trace'))->toBe('abc');
});

it('acts as a managed account on /v1 only', function () {
    [$connection, $transport] = scripted(accountId: 'acc-1');

    $connection->request('x', 'GET', '/v1/account');
    $connection->request('y', 'GET', '/partner/v1/accounts');

    expect($transport->requests[0]->header('MyComm-Account'))->toBe('acc-1')
        ->and($transport->requests[1]->header('MyComm-Account'))->toBeNull();
});

it('waits as long as Retry-After says on a 429', function () {
    [$connection, $transport] = scripted([errorResponse(429, 'RATE_LIMITED', headers: ['Retry-After' => '7']), new Response(200, [], '{"ok":true}')]);

    $response = $connection->request('x', 'GET', '/v1/x');

    expect($response->json())->toBe(['ok' => true])
        ->and($transport->slept)->toBe([7.0]);
});

it('backs off exponentially without Retry-After', function () {
    [$connection, $transport] = scripted([new Response(503), new Response(502), new Response(200, [], '{}')]);

    $connection->request('x', 'GET', '/v1/x');

    expect($transport->slept)->toHaveCount(2)
        ->and($transport->slept[0])->toBeBetween(0.375, 0.625)
        ->and($transport->slept[1])->toBeBetween(0.75, 1.25);
});

it('retries after a network error', function () {
    [$connection, $transport] = scripted([new ConnectionException('reset'), new Response(200, [], '{}')]);

    $connection->request('x', 'POST', '/v1/x');

    expect($transport->requests)->toHaveCount(2);
});

it('waits a second while the first request with the same key is still running', function () {
    [$connection, $transport] = scripted([errorResponse(409, 'IDEMPOTENCY_IN_PROGRESS'), new Response(202, [], '{}')]);

    $connection->request('x', 'POST', '/v1/x');

    expect($transport->slept)->toBe([1.0]);
});

it('gives up after the configured retries', function () {
    [$connection, $transport] = scripted([new Response(500), new Response(500), new Response(500), new Response(200, [], '{}')]);

    expect(fn () => $connection->request('x', 'GET', '/v1/x'))->toThrow(ServerException::class);
    expect($transport->requests)->toHaveCount(3);
});

it('lets the request options override the retries', function () {
    [$connection, $transport] = scripted([new Response(500), new Response(200, [], '{}')]);

    expect(fn () => $connection->request('x', 'GET', '/v1/x', options: new RequestOptions(maxRetries: 0)))->toThrow(ServerException::class);
    expect($transport->requests)->toHaveCount(1);
});

it('never retries an answer that would be the same again', function (int $status, string $code) {
    [$connection, $transport] = scripted([errorResponse($status, $code)]);

    expect(fn () => $connection->request('x', 'POST', '/v1/x'))->toThrow(ApiException::class);
    expect($transport->requests)->toHaveCount(1)
        ->and($transport->slept)->toBe([]);
})->with([
    [400, 'MALFORMED_REQUEST'],
    [401, 'UNAUTHENTICATED'],
    [403, 'ACCOUNT_SUSPENDED'],
    [404, 'NOT_FOUND'],
    [409, 'REFERENCE_ID_EXISTS'],
    [409, 'IDEMPOTENCY_KEY_REUSED'],
    [422, 'RECIPIENT_OPTED_OUT'],
]);

it('throws the exception that matches the status', function (int $status, string $code, string $class) {
    [$connection] = scripted([errorResponse($status, $code, ['field' => 'x'])], maxRetries: 0);

    try {
        $connection->request('x', 'GET', '/v1/x');
        $this->fail('No exception was thrown.');
    } catch (ApiException $e) {
        expect($e)->toBeInstanceOf($class)
            ->and($e->status)->toBe($status)
            ->and($e->getCode())->toBe($status)
            ->and($e->errorCode)->toBe($code)
            ->and($e->getMessage())->toBe("Failed with {$code}.")
            ->and($e->details)->toBe(['field' => 'x'])
            ->and($e->requestId)->toBe('01923f80-5c6a-7b10-9c21-0d3e4f5a6b7d')
            ->and($e->response?->status)->toBe($status);
    }
})->with([
    [400, 'INVALID_CURSOR', BadRequestException::class],
    [401, 'UNAUTHENTICATED', AuthenticationException::class],
    [403, 'ABILITY_MISSING', PermissionDeniedException::class],
    [404, 'NOT_FOUND', NotFoundException::class],
    [409, 'REFERENCE_ID_EXISTS', ConflictException::class],
    [413, 'PAYLOAD_TOO_LARGE', PayloadTooLargeException::class],
    [422, 'RECIPIENT_OPTED_OUT', UnprocessableException::class],
    [422, 'VALIDATION_FAILED', ValidationException::class],
    [429, 'RATE_LIMITED', RateLimitException::class],
    [500, 'INTERNAL_ERROR', ServerException::class],
    [503, 'SERVICE_UNAVAILABLE', ServerException::class],
    [418, 'SOMETHING_NEW', ApiException::class],
]);

it('falls back on the status for an error without the envelope', function () {
    [$connection] = scripted([new Response(502, ['MyComm-Request-Id' => 'req-9'], '<html>Bad Gateway</html>')], maxRetries: 0);

    try {
        $connection->request('x', 'GET', '/v1/x');
        $this->fail('No exception was thrown.');
    } catch (ApiException $e) {
        expect($e)->toBeInstanceOf(ServerException::class)
            ->and($e->errorCode)->toBe('')
            ->and($e->getMessage())->toBe('The MyComm API answered with HTTP 502.')
            ->and($e->requestId)->toBe('req-9');
    }
});

it('compares the error code with an enum case or a string', function () {
    [$connection] = scripted([errorResponse(422, 'RECIPIENT_OPTED_OUT')]);

    try {
        $connection->request('x', 'POST', '/v1/x');
    } catch (ApiException $e) {
        expect($e->is(ErrorCode::RecipientOptedOut))->toBeTrue()
            ->and($e->is('RECIPIENT_OPTED_OUT'))->toBeTrue()
            ->and($e->is(ErrorCode::NotFound))->toBeFalse();
    }
});

it('exposes the violations of a validation error per field', function () {
    [$connection] = scripted([errorResponse(422, 'VALIDATION_FAILED', ['fields' => [
        'to' => [['code' => 'INVALID_NUMBER', 'message' => '+92300123 is not a valid phone number.']],
        'messages.3.body' => [['code' => 'REQUIRED', 'message' => 'body or template_id is required.']],
    ]])]);

    try {
        $connection->request('x', 'POST', '/v1/x');
        $this->fail('No exception was thrown.');
    } catch (ValidationException $e) {
        expect($e->fields())->toHaveKeys(['to', 'messages.3.body'])
            ->and($e->fields()['to'][0])->toBe(['code' => 'INVALID_NUMBER', 'message' => '+92300123 is not a valid phone number.'])
            ->and($e->messagesFor('messages.3.body'))->toBe(['body or template_id is required.'])
            ->and($e->messagesFor('body'))->toBe([]);
    }
});

it('tells how long a rate limit asks to wait', function () {
    [$connection] = scripted([errorResponse(429, 'RATE_LIMITED', headers: ['Retry-After' => '12'])], maxRetries: 0);

    try {
        $connection->request('x', 'GET', '/v1/x');
        $this->fail('No exception was thrown.');
    } catch (RateLimitException $e) {
        expect($e->retryAfter())->toBe(12);
    }
});

it('refuses a success answer that is not JSON', function () {
    [$connection] = scripted([new Response(200, [], '<html>captive portal</html>')]);

    expect(fn () => $connection->request('x', 'GET', '/v1/x')->json())->toThrow(UnexpectedResponseException::class);
});

it('reads the response metadata', function () {
    [$connection] = scripted([new Response(202, [
        'MyComm-Request-Id' => 'req-1',
        'RateLimit-Limit' => '600',
        'RateLimit-Remaining' => '598',
        'RateLimit-Reset' => '41',
        'Idempotent-Replayed' => 'true',
        'Deprecation' => '@1790000000',
        'Sunset' => 'Wed, 01 Dec 2027 00:00:00 GMT',
    ], '{}')]);

    $meta = $connection->request('x', 'POST', '/v1/x')->meta();

    expect($meta->status)->toBe(202)
        ->and($meta->requestId)->toBe('req-1')
        ->and($meta->rateLimitLimit)->toBe(600)
        ->and($meta->rateLimitRemaining)->toBe(598)
        ->and($meta->rateLimitReset)->toBe(41)
        ->and($meta->idempotentReplayed)->toBeTrue()
        ->and($meta->deprecation)->toBe('@1790000000')
        ->and($meta->sunset)->toBe('Wed, 01 Dec 2027 00:00:00 GMT')
        ->and($meta->headers)->toHaveKey('mycomm-request-id');
});

it('encodes a multipart body with files and plain fields', function () {
    [$connection, $transport] = scripted();

    $connection->request('imports.create', 'POST', '/v1/imports', multipart: [
        'type' => 'contacts_csv',
        'file' => FileUpload::fromString("phone\n+923001234567\n", 'my "contacts".csv', 'text/csv'),
        'options' => ['mapping' => ['phone' => 'phone']],
        'skipped' => null,
    ]);

    $request = $transport->requests[0];
    preg_match('/boundary=(.+)$/', (string) $request->header('Content-Type'), $match);
    $boundary = $match[1];

    expect($request->body)->toBe(
        "--{$boundary}\r\nContent-Disposition: form-data; name=\"type\"\r\n\r\ncontacts_csv\r\n"
        ."--{$boundary}\r\nContent-Disposition: form-data; name=\"file\"; filename=\"my %22contacts%22.csv\"\r\nContent-Type: text/csv\r\n\r\nphone\n+923001234567\n\r\n"
        ."--{$boundary}\r\nContent-Disposition: form-data; name=\"options\"\r\n\r\n{\"mapping\":{\"phone\":\"phone\"}}\r\n"
        ."--{$boundary}--\r\n"
    )->and($request->json)->toBeNull()
        ->and($request->header('Idempotency-Key'))->not->toBeNull();
});
