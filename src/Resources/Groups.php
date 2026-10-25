<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Resources;

use Generator;
use MyComm\Exceptions\ApiException;
use MyComm\Exceptions\ConnectionException;
use MyComm\Exceptions\InvalidArgumentException;
use MyComm\Http\Connection;
use MyComm\Http\Params;
use MyComm\Models;
use MyComm\Omit;
use MyComm\Page;
use MyComm\RequestOptions;

final class Groups
{
    public readonly Groups\Members $members;

    public function __construct(private readonly Connection $connection)
    {
        $this->members = new Groups\Members($connection);
    }

    /**
     * List groups.
     *
     * Requires `contacts:read`. Groups of the account sorted by `name` (default) with cursor pagination. `q`
     * matches the name, case-insensitively.
     *
     * @param  ?string  $q  Name contains, at least 2 characters.
     * @param  ?string  $sort  One of: `name`, `-name`.
     * @param  ?int  $limit  Page size, 1 to 200.
     * @param  ?string  $cursor  Opaque cursor from a previous page's `next_cursor`; valid for 24 hours.
     * @return Page<Models\Group>
     *
     * @throws InvalidArgumentException when an argument breaks a rule the API documents; nothing is sent (client option `validate`)
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
        $this->connection->guard(
            'groups.list',
            [
                'q' => $q,
                'limit' => $limit,
            ],
            [
                'q' => [
                    'minLength' => 2,
                ],
                'limit' => [
                    'min' => 1,
                    'max' => 200,
                ],
            ],
            [],
        );

        $response = $this->connection->request(
            'groups.list',
            'GET',
            '/v1/groups',
            query: Params::query([
                'q' => $q,
                'sort' => $sort,
                'limit' => $limit,
                'cursor' => $cursor,
            ]),
            options: $requestOptions,
        );

        return Page::fromResponse($response, static fn (array $item): Models\Group => Models\Group::fromArray($item), fn (string $next): Page => $this->list(q: $q, sort: $sort, limit: $limit, cursor: $next, requestOptions: $requestOptions));
    }

    /**
     * Every item of `list()`, fetching the following pages as the loop reaches them.
     *
     * `limit` is the page size, not a cap on the items.
     *
     * @param  ?string  $q  Name contains, at least 2 characters.
     * @param  ?string  $sort  One of: `name`, `-name`.
     * @param  ?int  $limit  Page size, 1 to 200.
     * @return Generator<int, Models\Group>
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
     * Create a group.
     *
     * Requires `groups:write`. The name is unique per account, ignoring case (`409 NAME_TAKEN`).
     *
     * @throws InvalidArgumentException when an argument breaks a rule the API documents; nothing is sent (client option `validate`)
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function create(
        string $name,
        string|Omit|null $description = Omit::Value,
        ?RequestOptions $requestOptions = null,
    ): Models\Group {
        $this->connection->guard(
            'groups.create',
            [
                'name' => $name,
                'description' => $description,
            ],
            [
                'name' => [
                    'minLength' => 1,
                    'maxLength' => 100,
                ],
                'description' => [
                    'maxLength' => 255,
                ],
            ],
            [],
        );

        $response = $this->connection->request(
            'groups.create',
            'POST',
            '/v1/groups',
            body: Params::body([
                'name' => $name,
                'description' => $description,
            ]),
            options: $requestOptions,
        );

        return Models\Group::fromArray($response->json(), $response->meta());
    }

    /**
     * Retrieve a group.
     *
     * Requires `contacts:read`.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function show(
        string $id,
        ?RequestOptions $requestOptions = null,
    ): Models\Group {
        $response = $this->connection->request('groups.show', 'GET', '/v1/groups/'.rawurlencode($id), options: $requestOptions);

        return Models\Group::fromArray($response->json(), $response->meta());
    }

    /**
     * Update a group.
     *
     * Requires `groups:write`. Changes `name` and `description`; a duplicate name answers `409 NAME_TAKEN`.
     *
     * @throws InvalidArgumentException when an argument breaks a rule the API documents; nothing is sent (client option `validate`)
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function update(
        string $id,
        ?string $name = null,
        string|Omit|null $description = Omit::Value,
        ?RequestOptions $requestOptions = null,
    ): Models\Group {
        $this->connection->guard(
            'groups.update',
            [
                'name' => $name,
                'description' => $description,
            ],
            [
                'name' => [
                    'minLength' => 1,
                    'maxLength' => 100,
                ],
                'description' => [
                    'maxLength' => 255,
                ],
            ],
            [],
        );

        $response = $this->connection->request(
            'groups.update',
            'PATCH',
            '/v1/groups/'.rawurlencode($id),
            body: Params::body([
                'name' => $name ?? Omit::Value,
                'description' => $description,
            ]),
            options: $requestOptions,
        );

        return Models\Group::fromArray($response->json(), $response->meta());
    }

    /**
     * Delete a group.
     *
     * Requires `groups:write`. Removes the group and its memberships; the contacts stay. Refused with `409`
     * `INVALID_STATE` and `details.used_by` (each `{type, id, name}`) while a scheduled, running or paused
     * campaign uses the group or an automation rule's add-to-group or remove-from-group action names it.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function delete(
        string $id,
        ?RequestOptions $requestOptions = null,
    ): void {
        $this->connection->request('groups.delete', 'DELETE', '/v1/groups/'.rawurlencode($id), options: $requestOptions);
    }
}
