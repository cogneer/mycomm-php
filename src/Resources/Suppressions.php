<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Resources;

use DateTimeImmutable;
use DateTimeInterface;
use Generator;
use MyComm\Enums;
use MyComm\Exceptions\ApiException;
use MyComm\Exceptions\ConnectionException;
use MyComm\FileUpload;
use MyComm\Http\Connection;
use MyComm\Http\Params;
use MyComm\Input;
use MyComm\Inputs;
use MyComm\Model;
use MyComm\Models;
use MyComm\Omit;
use MyComm\Page;
use MyComm\RequestOptions;

final class Suppressions
{
    public function __construct(private readonly Connection $connection)
    {
    }

    /**
     * List suppressions.
     *
     * Requires `suppressions:read`. The account's suppressed identities, newest first, with cursor pagination.
     * Suppressions belong to the account, not to an inbox: a restricted key sees the whole list. Comma lists
     * match any of the values. A phone number in `address` written without country code is read in the account
     * country. Only `marketing` traffic is blocked by a suppression.
     *
     * @param  ?string  $address  Suppressed address, E.164 for phone numbers (comma list).
     * @param  ?string  $reason  Comma list of `keyword`, `manual`, `import`, `api`, `complaint`, `dnc`, `invalid`.
     * @param  DateTimeInterface|string|null  $created_after  Inclusive lower bound.
     * @param  DateTimeInterface|string|null  $created_before  Exclusive upper bound.
     * @param  ?string  $sort  One of: `-created_at`, `created_at`.
     * @param  ?int  $limit  Page size, 1 to 200.
     * @param  ?string  $cursor  Opaque cursor from a previous page's `next_cursor`; valid for 24 hours.
     * @return Page<Models\Suppression>
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function list(
        ?string $address = null,
        ?string $reason = null,
        DateTimeInterface|string|null $created_after = null,
        DateTimeInterface|string|null $created_before = null,
        ?string $sort = null,
        ?int $limit = null,
        ?string $cursor = null,
        ?RequestOptions $requestOptions = null,
    ): Page {
        $response = $this->connection->request(
            'suppressions.list',
            'GET',
            '/v1/suppressions',
            query: Params::query([
                'address' => $address,
                'reason' => $reason,
                'created_after' => $created_after,
                'created_before' => $created_before,
                'sort' => $sort,
                'limit' => $limit,
                'cursor' => $cursor,
            ]),
            options: $requestOptions,
        );

        return Page::fromResponse($response, static fn (array $item): Models\Suppression => Models\Suppression::fromArray($item), fn (string $next): Page => $this->list(address: $address, reason: $reason, created_after: $created_after, created_before: $created_before, sort: $sort, limit: $limit, cursor: $next, requestOptions: $requestOptions));
    }

    /**
     * Every item of `list()`, fetching the following pages as the loop reaches them.
     *
     * `limit` is the page size, not a cap on the items.
     *
     * @param  ?string  $address  Suppressed address, E.164 for phone numbers (comma list).
     * @param  ?string  $reason  Comma list of `keyword`, `manual`, `import`, `api`, `complaint`, `dnc`, `invalid`.
     * @param  DateTimeInterface|string|null  $created_after  Inclusive lower bound.
     * @param  DateTimeInterface|string|null  $created_before  Exclusive upper bound.
     * @param  ?string  $sort  One of: `-created_at`, `created_at`.
     * @param  ?int  $limit  Page size, 1 to 200.
     * @return Generator<int, Models\Suppression>
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function all(
        ?string $address = null,
        ?string $reason = null,
        DateTimeInterface|string|null $created_after = null,
        DateTimeInterface|string|null $created_before = null,
        ?string $sort = null,
        ?int $limit = null,
        ?RequestOptions $requestOptions = null,
    ): Generator {
        return $this->list(address: $address, reason: $reason, created_after: $created_after, created_before: $created_before, sort: $sort, limit: $limit, requestOptions: $requestOptions)->autoPaging();
    }

    /**
     * Suppress a number.
     *
     * Requires `suppressions:write` (never held by a restricted key, 03 section 2.2). `identity_type` defaults
     * to `phone` (the only type in v1) and `reason` to `api`; the API may write `api`, `complaint` or `dnc`.
     * The address is stored in E.164 (a number without country code is read in the account country; one that
     * is not a valid number is `422 VALIDATION_FAILED` with `INVALID_NUMBER` on `address`). A new suppression
     * answers `201`, writes a `consent_events` `opt_out` (method `api`) and emits `contact.opted_out`; the
     * identity's queued marketing is cancelled. A number that is already suppressed, for any reason, answers
     * `200` with the existing row unchanged. With the account setting `opt_out.scope: contact`, a number that
     * belongs to a contact suppresses every identity of that contact not suppressed yet, each with its own
     * consent event and `contact.opted_out`, as a STOP reply does; the answer is still the row of the number
     * given.
     *
     * @param  string  $address  E.164 number, e.g. `+923451234567`; a number without country code is read in the account country.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function create(
        string $address,
        string|Omit|null $identity_type = Omit::Value,
        Enums\SuppressionCreateReason|string|Omit|null $reason = Omit::Value,
        string|Omit|null $note = Omit::Value,
        ?RequestOptions $requestOptions = null,
    ): Models\Suppression {
        $response = $this->connection->request(
            'suppressions.create',
            'POST',
            '/v1/suppressions',
            body: Params::body([
                'identity_type' => $identity_type,
                'address' => $address,
                'reason' => $reason,
                'note' => $note,
            ]),
            options: $requestOptions,
        );

        return Models\Suppression::fromArray($response->json(), $response->meta());
    }

    /**
     * Remove a suppression.
     *
     * Requires `suppressions:write`. Deletes the suppression, writes a `consent_events` `opt_in` (method
     * `api`, the note) and emits `contact.opted_in`; the `opt_out` consent event stays (the consent trail is
     * append-only). `consent_note` is required when the suppression's `reason` is `keyword`: without it the
     * answer is `422 CONSENT_NOTE_REQUIRED` and the number stays suppressed. `204`. A suppression that is
     * absent or in another account is `404`.
     *
     * @param  string|Omit|null  $consent_note  How the contact agreed to receive marketing again. Required to remove a `keyword` suppression.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function remove(
        string $id,
        string|Omit|null $consent_note = Omit::Value,
        ?RequestOptions $requestOptions = null,
    ): void {
        $this->connection->request(
            'suppressions.remove',
            'POST',
            '/v1/suppressions/'.rawurlencode($id).'/remove',
            body: Params::body([
                'consent_note' => $consent_note,
            ]),
            options: $requestOptions,
        );
    }

    /**
     * Check whether an address is suppressed.
     *
     * Requires `suppressions:read`. `address` is the E.164 number (a number without country code is read in
     * the account country; one that is not a valid number is `422 VALIDATION_FAILED` with `INVALID_NUMBER` on
     * `address`). `identity_type` defaults to `phone`. The answer echoes the normalised address.
     *
     * @param  string  $address  The address to check, e.g. `+923451234567`.
     * @param  ?string  $identity_type  One of: `phone`, `sender_id`, `whatsapp`, `telegram`.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function check(
        string $address,
        ?string $identity_type = null,
        ?RequestOptions $requestOptions = null,
    ): Models\SuppressionCheck {
        $response = $this->connection->request(
            'suppressions.check',
            'GET',
            '/v1/suppressions/check',
            query: Params::query([
                'address' => $address,
                'identity_type' => $identity_type,
            ]),
            options: $requestOptions,
        );

        return Models\SuppressionCheck::fromArray($response->json(), $response->meta());
    }
}
