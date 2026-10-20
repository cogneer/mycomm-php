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

final class ConsentEvents
{
    public function __construct(private readonly Connection $connection)
    {
    }

    /**
     * List consent events.
     *
     * Requires `suppressions:read`. The account's consent audit trail, newest first, with cursor pagination
     * (03 sections 2.5 and 3.10): every `opt_out` and `opt_in` with its method, keyword, message, actor and
     * note. The trail is append-only; it outlives the suppression it records and the contact's erasure. Comma
     * lists match any of the values. A phone number in `address` written without country code is read in the
     * account country.
     *
     * @param  ?string  $address  E.164 for phone numbers (comma list).
     * @param  ?string  $event  Comma list of `opt_out`, `opt_in`.
     * @param  ?string  $method  Comma list of `keyword`, `manual`, `api`, `import`, `campaign_form`.
     * @param  DateTimeInterface|string|null  $created_after  Inclusive lower bound.
     * @param  DateTimeInterface|string|null  $created_before  Exclusive upper bound.
     * @param  ?string  $sort  One of: `-created_at`, `created_at`.
     * @param  ?int  $limit  Page size, 1 to 200.
     * @param  ?string  $cursor  Opaque cursor from a previous page's `next_cursor`; valid for 24 hours.
     * @return Page<Models\ConsentEvent>
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function list(
        ?string $address = null,
        ?string $event = null,
        ?string $method = null,
        DateTimeInterface|string|null $created_after = null,
        DateTimeInterface|string|null $created_before = null,
        ?string $sort = null,
        ?int $limit = null,
        ?string $cursor = null,
        ?RequestOptions $requestOptions = null,
    ): Page {
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
     * @param  ?string  $address  E.164 for phone numbers (comma list).
     * @param  ?string  $event  Comma list of `opt_out`, `opt_in`.
     * @param  ?string  $method  Comma list of `keyword`, `manual`, `api`, `import`, `campaign_form`.
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
        ?string $address = null,
        ?string $event = null,
        ?string $method = null,
        DateTimeInterface|string|null $created_after = null,
        DateTimeInterface|string|null $created_before = null,
        ?string $sort = null,
        ?int $limit = null,
        ?RequestOptions $requestOptions = null,
    ): Generator {
        return $this->list(address: $address, event: $event, method: $method, created_after: $created_after, created_before: $created_before, sort: $sort, limit: $limit, requestOptions: $requestOptions)->autoPaging();
    }
}
