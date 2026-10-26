<?php

declare(strict_types=1);

use MyComm\Enums\ErrorCode;
use MyComm\Exceptions\ApiException;
use MyComm\Exceptions\InvalidHeaderException;
use MyComm\Exceptions\RateLimitException;
use MyComm\Exceptions\UnsupportedMediaTypeException;
use MyComm\Exceptions\ValidationException;
use MyComm\Http\RetryAfter;
use MyComm\Testing\FakeResponse;
use MyComm\Testing\MyCommFake;

function thrown(FakeResponse $response): ApiException
{
    $fake = new MyCommFake;
    $fake->respond('messages.send', $response);
    try {
        $fake->client()->messages->send(to: '+923001234567', body: 'Hi');
    } catch (ApiException $e) {
        return $e;
    }
    throw new RuntimeException('Expected an ApiException.');
}

it('summarises an error in one line with everything support asks for', function () {
    $e = thrown(FakeResponse::error(422, 'RECIPIENT_OPTED_OUT', 'The recipient +923001234567 opted out of marketing messages.', ['address' => '+923001234567']));

    expect($e->summary())->toContain('RECIPIENT_OPTED_OUT (422): The recipient +923001234567 opted out')
        ->toContain('"address":"+923001234567"')
        ->and((string) $e)->toContain(': RECIPIENT_OPTED_OUT (422)');
});

it('knows the code as an enum, or null when the SDK has not heard of it', function () {
    expect(thrown(FakeResponse::error(422, 'RECIPIENT_OPTED_OUT'))->code())->toBe(ErrorCode::RecipientOptedOut)
        ->and(thrown(FakeResponse::error(422, 'A_CODE_FROM_THE_FUTURE'))->code())->toBeNull()
        ->and(thrown(FakeResponse::error(422, 'RECIPIENT_OPTED_OUT'))->isAnyOf(ErrorCode::InvalidNumber, 'RECIPIENT_OPTED_OUT'))->toBeTrue();
});

it('lists each field of a validation error in the summary', function () {
    $e = thrown(FakeResponse::error(422, 'VALIDATION_FAILED', 'The request has invalid fields.', ['fields' => ['to' => [['code' => 'REQUIRED_WITH', 'message' => 'Send to (a phone number) or contact_id.']]]]));

    expect($e)->toBeInstanceOf(ValidationException::class)
        ->and($e->summary())->toContain('to: REQUIRED_WITH: Send to (a phone number) or contact_id.');
});

it('has a type for a bad header and for a wrong content type', function () {
    expect(thrown(FakeResponse::error(400, 'INVALID_HEADER', 'Idempotency-Key is empty.', ['header' => 'Idempotency-Key'])))->toBeInstanceOf(InvalidHeaderException::class)
        ->and(thrown(FakeResponse::error(415, 'UNSUPPORTED_MEDIA_TYPE', 'Send JSON.', ['expected' => 'application/json'])))->toBeInstanceOf(UnsupportedMediaTypeException::class);
});

it('reads Retry-After as seconds or as an HTTP date', function () {
    expect(RetryAfter::seconds('30'))->toBe(30)
        ->and(RetryAfter::seconds('Wed, 21 Oct 2026 07:28:30 GMT', (int) strtotime('Wed, 21 Oct 2026 07:28:00 GMT')))->toBe(30)
        ->and(RetryAfter::seconds('Wed, 21 Oct 2026 07:27:00 GMT', (int) strtotime('Wed, 21 Oct 2026 07:28:00 GMT')))->toBe(0)
        ->and(RetryAfter::seconds('soon'))->toBeNull()
        ->and(RetryAfter::seconds(null))->toBeNull();

    $e = thrown(FakeResponse::error(429, 'RATE_LIMITED', 'Slow down.', [], ['Retry-After' => '12']));
    expect($e)->toBeInstanceOf(RateLimitException::class)->and($e->retryAfter())->toBe(12);
});
