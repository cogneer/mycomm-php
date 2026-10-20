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

final class SmartLists
{
    public readonly SmartLists\Members $members;

    public function __construct(private readonly Connection $connection)
    {
        $this->members = new SmartLists\Members($connection);
    }

    /**
     * List smart lists.
     *
     * Requires `contacts:read`. Smart lists of the account sorted by `name` (default) with cursor pagination.
     * `q` matches the name, case-insensitively.
     *
     * @param  ?string  $q  Name contains, at least 2 characters.
     * @param  ?string  $sort  One of: `name`, `-name`.
     * @param  ?int  $limit  Page size, 1 to 200.
     * @param  ?string  $cursor  Opaque cursor from a previous page's `next_cursor`; valid for 24 hours.
     * @return Page<Models\SmartList>
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function list(
        ?string $q = null,
        ?string $sort = null,
        ?int $limit = null,
        ?string $cursor = null,
        ?RequestOptions $requestOptions = null,
    ): Page {
        $response = $this->connection->request(
            'smartLists.list',
            'GET',
            '/v1/smart-lists',
            query: Params::query([
                'q' => $q,
                'sort' => $sort,
                'limit' => $limit,
                'cursor' => $cursor,
            ]),
            options: $requestOptions,
        );

        return Page::fromResponse($response, static fn (array $item): Models\SmartList => Models\SmartList::fromArray($item), fn (string $next): Page => $this->list(q: $q, sort: $sort, limit: $limit, cursor: $next, requestOptions: $requestOptions));
    }

    /**
     * Every item of `list()`, fetching the following pages as the loop reaches them.
     *
     * `limit` is the page size, not a cap on the items.
     *
     * @param  ?string  $q  Name contains, at least 2 characters.
     * @param  ?string  $sort  One of: `name`, `-name`.
     * @param  ?int  $limit  Page size, 1 to 200.
     * @return Generator<int, Models\SmartList>
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function all(
        ?string $q = null,
        ?string $sort = null,
        ?int $limit = null,
        ?RequestOptions $requestOptions = null,
    ): Generator {
        return $this->list(q: $q, sort: $sort, limit: $limit, requestOptions: $requestOptions)->autoPaging();
    }

    /**
     * Create a smart list.
     *
     * Requires `groups:write`. `filter` is the smart list DSL of 02 section 9; a filter that breaks it answers
     * `422 FILTER_INVALID` with `details.pointer`, a JSON pointer to the bad node. The name is unique per
     * account, ignoring case (`409 NAME_TAKEN`).
     *
     * @param  Inputs\SmartListFilter|array<string, mixed>  $filter
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function create(
        string $name,
        Inputs\SmartListFilter|array $filter,
        ?RequestOptions $requestOptions = null,
    ): Models\SmartList {
        $response = $this->connection->request(
            'smartLists.create',
            'POST',
            '/v1/smart-lists',
            body: Params::body([
                'name' => $name,
                'filter' => Params::object($filter),
            ]),
            options: $requestOptions,
        );

        return Models\SmartList::fromArray($response->json(), $response->meta());
    }

    /**
     * Preview a filter.
     *
     * Requires `contacts:read`. Counts the contacts a filter matches and returns up to 10 of them, newest
     * first, without saving anything; for filter-builder UIs. Contacts are evaluated on their primary phone
     * identity. A count that runs past its 5-second limit answers `503 SERVICE_UNAVAILABLE`; retry after
     * `Retry-After` seconds.
     *
     * @param  Inputs\SmartListFilter|array<string, mixed>  $filter
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function preview(
        Inputs\SmartListFilter|array $filter,
        ?RequestOptions $requestOptions = null,
    ): Models\SmartListPreview {
        $response = $this->connection->request(
            'smartLists.preview',
            'POST',
            '/v1/smart-lists/preview',
            body: Params::body([
                'filter' => Params::object($filter),
            ]),
            options: $requestOptions,
        );

        return Models\SmartListPreview::fromArray($response->json(), $response->meta());
    }

    /**
     * Retrieve a smart list.
     *
     * Requires `contacts:read`.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function show(
        string $id,
        ?RequestOptions $requestOptions = null,
    ): Models\SmartList {
        $response = $this->connection->request('smartLists.show', 'GET', '/v1/smart-lists/'.rawurlencode($id), options: $requestOptions);

        return Models\SmartList::fromArray($response->json(), $response->meta());
    }

    /**
     * Update a smart list.
     *
     * Requires `groups:write`. Changes `name` and `filter`; an invalid filter answers `422 FILTER_INVALID` and
     * a duplicate name `409 NAME_TAKEN`. Any change starts a fresh count.
     *
     * @param  Inputs\SmartListFilter|array<string, mixed>|null  $filter
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function update(
        string $id,
        ?string $name = null,
        Inputs\SmartListFilter|array|null $filter = null,
        ?RequestOptions $requestOptions = null,
    ): Models\SmartList {
        $response = $this->connection->request(
            'smartLists.update',
            'PATCH',
            '/v1/smart-lists/'.rawurlencode($id),
            body: Params::body([
                'name' => $name ?? Omit::Value,
                'filter' => Params::object($filter ?? Omit::Value),
            ]),
            options: $requestOptions,
        );

        return Models\SmartList::fromArray($response->json(), $response->meta());
    }

    /**
     * Delete a smart list.
     *
     * Requires `groups:write`. Refused with `409 INVALID_STATE` and `details.used_by` (each `{type, id,
     * name}`) while an unfinished campaign (draft, scheduled, running or paused) targets the list.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function delete(
        string $id,
        ?RequestOptions $requestOptions = null,
    ): void {
        $this->connection->request('smartLists.delete', 'DELETE', '/v1/smart-lists/'.rawurlencode($id), options: $requestOptions);
    }

    /**
     * Count the contacts of a smart list.
     *
     * Requires `contacts:read`. The number of contacts the filter matches, cached for 10 minutes: contacts
     * that start or stop matching show within 10 minutes, while a change to the list is counted at once. A
     * count that runs past its 5-second limit answers `503 SERVICE_UNAVAILABLE`; retry after `Retry-After`
     * seconds.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function count(
        string $id,
        ?RequestOptions $requestOptions = null,
    ): Models\SmartListsCountResponse {
        $response = $this->connection->request('smartLists.count', 'GET', '/v1/smart-lists/'.rawurlencode($id).'/count', options: $requestOptions);

        return Models\SmartListsCountResponse::fromArray($response->json(), $response->meta());
    }
}
