<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Resources;

use DateTimeInterface;
use Generator;
use MyComm\Exceptions\ApiException;
use MyComm\Exceptions\ConnectionException;
use MyComm\Exceptions\InvalidArgumentException;
use MyComm\Http\Connection;
use MyComm\Http\Params;
use MyComm\Models;
use MyComm\Page;
use MyComm\RequestOptions;

final class ConsentEvents
{
    public function __construct(private readonly Connection $connection) {}

    /**
     * List consent events.
     *
     * Requires `suppressions:read`. The account's consent audit trail, newest first, with cursor pagination:
     * every `opt_out` and `opt_in` with its method, keyword, message, actor and note. The trail is
     * append-only; it outlives the suppression it records and the contact's erasure. Comma lists match any of
     * the values. A phone number in `address` written without country code is read in the account country.
     *
     * @param  list<string>|string|null  $address  E.164 for phone numbers (comma list).
     * @param  list<string>|string|null  $event  Comma list of `opt_out`, `opt_in`.
     * @param  list<string>|string|null  $method  Comma list of `keyword`, `manual`, `api`, `import`, `campaign_form`.
     * @param  DateTimeInterface|string|null  $created_after  Inclusive lower bound.
     * @param  DateTimeInterface|string|null  $created_before  Exclusive upper bound.
     * @param  ?string  $sort  One of: `-created_at`, `created_at`.
     * @param  ?int  $limit  Page size, 1 to 200.
     * @param  ?string  $cursor  Opaque cursor from a previous page's `next_cursor`; valid for 24 hours.
     * @return Page<Models\ConsentEvent>
     *
     * @throws InvalidArgumentException when an argument breaks a rule the API documents; nothing is sent (client option `validate`)
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function list(
        array|string|null $address = null,
        array|string|null $event = null,
        array|string|null $method = null,
        DateTimeInterface|string|null $created_after = null,
        DateTimeInterface|string|null $created_before = null,
        ?string $sort = null,
        ?int $limit = null,
        ?string $cursor = null,
        ?RequestOptions $requestOptions = null,
    ): Page {
        $this->connection->guard(
            'consentEvents.list',
            [
                'limit' => $limit,
            ],
            [
                'limit' => [
                    'min' => 1,
                    'max' => 200,
                ],
            ],
            [],
        );

        $response = $this->connection->request(
            'consentEvents.list',
            'GET',
            '/v1/consent-events',
            query: Params::query([
                'address' => $address,
                'event' => $event,
                'method' => $method,
                'created_after' => $created_after,
                'created_before' => $created_before,
                'sort' => $sort,
                'limit' => $limit,
                'cursor' => $cursor,
            ]),
            options: $requestOptions,
        );

        return Page::fromResponse($response, static fn (array $item): Models\ConsentEvent => Models\ConsentEvent::fromArray($item), fn (string $next): Page => $this->list(address: $address, event: $event, method: $method, created_after: $created_after, created_before: $created_before, sort: $sort, limit: $limit, cursor: $next, requestOptions: $requestOptions));
    }

    /**
     * Every item of `list()`, fetching the following pages as the loop reaches them.
     *
     * `limit` is the page size, not a cap on the items.
     *
     * @param  list<string>|string|null  $address  E.164 for phone numbers (comma list).
     * @param  list<string>|string|null  $event  Comma list of `opt_out`, `opt_in`.
     * @param  list<string>|string|null  $method  Comma list of `keyword`, `manual`, `api`, `import`, `campaign_form`.
     * @param  DateTimeInterface|string|null  $created_after  Inclusive lower bound.
     * @param  DateTimeInterface|string|null  $created_before  Exclusive upper bound.
     * @param  ?string  $sort  One of: `-created_at`, `created_at`.
     * @param  ?int  $limit  Page size, 1 to 200.
     * @return Generator<int, Models\ConsentEvent>
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function all(
        array|string|null $address = null,
        array|string|null $event = null,
        array|string|null $method = null,
        DateTimeInterface|string|null $created_after = null,
        DateTimeInterface|string|null $created_before = null,
        ?string $sort = null,
        ?int $limit = null,
        ?RequestOptions $requestOptions = null,
    ): Generator {
        return $this->list(address: $address, event: $event, method: $method, created_after: $created_after, created_before: $created_before, sort: $sort, limit: $limit, requestOptions: $requestOptions)->autoPaging();
    }
}
