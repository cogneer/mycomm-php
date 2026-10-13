<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Resources\Groups;

use DateTimeInterface;
use Generator;
use MyComm\Exceptions\ApiException;
use MyComm\Exceptions\ConnectionException;
use MyComm\Http\Connection;
use MyComm\Http\Params;
use MyComm\Models;
use MyComm\Page;
use MyComm\RequestOptions;

final class Members
{
    public function __construct(private readonly Connection $connection) {}

    /**
     * List the contacts of a group.
     *
     * Requires `contacts:read`. The contacts of the group with the filters and resource of `GET /v1/contacts`;
     * soft-deleted contacts are not listed.
     *
     * @param  ?string  $q  Name, email or address contains, at least 2 characters.
     * @param  ?string  $address  Matches any identity address (comma list).
     * @param  ?string  $identity_type  Comma list.
     * @param  ?string  $group_id  Further narrows to members of these groups (comma list).
     * @param  ?string  $source  Comma list.
     * @param  ?string  $language  Comma list.
     * @param  ?bool  $blocked  `true` = any identity is blocked.
     * @param  DateTimeInterface|string|null  $created_after  Inclusive lower bound.
     * @param  DateTimeInterface|string|null  $created_before  Exclusive upper bound.
     * @param  DateTimeInterface|string|null  $updated_after  Inclusive lower bound.
     * @param  ?string  $sort  One of: `-created_at`, `created_at`, `display_name`, `-display_name`, `-updated_at`, `updated_at`.
     * @param  ?int  $limit  Page size, 1 to 200.
     * @param  ?string  $cursor  Opaque cursor from a previous page's `next_cursor`; valid for 24 hours.
     * @return Page<Models\Contact>
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function list(
        string $id,
        ?string $q = null,
        ?string $address = null,
        ?string $identity_type = null,
        ?string $group_id = null,
        ?string $source = null,
        ?string $language = null,
        ?bool $blocked = null,
        DateTimeInterface|string|null $created_after = null,
        DateTimeInterface|string|null $created_before = null,
        DateTimeInterface|string|null $updated_after = null,
        ?string $sort = null,
        ?int $limit = null,
        ?string $cursor = null,
        ?RequestOptions $requestOptions = null,
    ): Page {
        $response = $this->connection->request(
            'groups.members.list',
            'GET',
            '/v1/groups/'.rawurlencode($id).'/members',
            query: Params::query([
                'q' => $q,
                'address' => $address,
                'identity_type' => $identity_type,
                'group_id' => $group_id,
                'source' => $source,
                'language' => $language,
                'blocked' => $blocked,
                'created_after' => $created_after,
                'created_before' => $created_before,
                'updated_after' => $updated_after,
                'sort' => $sort,
                'limit' => $limit,
                'cursor' => $cursor,
            ]),
            options: $requestOptions,
        );

        return Page::fromResponse($response, static fn (array $item): Models\Contact => Models\Contact::fromArray($item), fn (string $next): Page => $this->list(id: $id, q: $q, address: $address, identity_type: $identity_type, group_id: $group_id, source: $source, language: $language, blocked: $blocked, created_after: $created_after, created_before: $created_before, updated_after: $updated_after, sort: $sort, limit: $limit, cursor: $next, requestOptions: $requestOptions));
    }

    /**
     * Every item of `list()`, fetching the following pages as the loop reaches them.
     *
     * `limit` is the page size, not a cap on the items.
     *
     * @param  ?string  $q  Name, email or address contains, at least 2 characters.
     * @param  ?string  $address  Matches any identity address (comma list).
     * @param  ?string  $identity_type  Comma list.
     * @param  ?string  $group_id  Further narrows to members of these groups (comma list).
     * @param  ?string  $source  Comma list.
     * @param  ?string  $language  Comma list.
     * @param  ?bool  $blocked  `true` = any identity is blocked.
     * @param  DateTimeInterface|string|null  $created_after  Inclusive lower bound.
     * @param  DateTimeInterface|string|null  $created_before  Exclusive upper bound.
     * @param  DateTimeInterface|string|null  $updated_after  Inclusive lower bound.
     * @param  ?string  $sort  One of: `-created_at`, `created_at`, `display_name`, `-display_name`, `-updated_at`, `updated_at`.
     * @param  ?int  $limit  Page size, 1 to 200.
     * @return Generator<int, Models\Contact>
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function all(
        string $id,
        ?string $q = null,
        ?string $address = null,
        ?string $identity_type = null,
        ?string $group_id = null,
        ?string $source = null,
        ?string $language = null,
        ?bool $blocked = null,
        DateTimeInterface|string|null $created_after = null,
        DateTimeInterface|string|null $created_before = null,
        DateTimeInterface|string|null $updated_after = null,
        ?string $sort = null,
        ?int $limit = null,
        ?RequestOptions $requestOptions = null,
    ): Generator {
        return $this->list(id: $id, q: $q, address: $address, identity_type: $identity_type, group_id: $group_id, source: $source, language: $language, blocked: $blocked, created_after: $created_after, created_before: $created_before, updated_after: $updated_after, sort: $sort, limit: $limit, requestOptions: $requestOptions)->autoPaging();
    }

    /**
     * Add contacts to a group.
     *
     * Requires `groups:write`. 1 to 1,000 contact ids. Unknown ids are reported in `not_found`; contacts
     * already in the group count in `already_member`. Each contact that joins emits `contact.updated` (changed
     * `groups`).
     *
     * @param  list<string>  $contact_ids
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function add(
        string $id,
        array $contact_ids,
        ?RequestOptions $requestOptions = null,
    ): Models\GroupsMembersAddResponse {
        $response = $this->connection->request(
            'groups.members.add',
            'POST',
            '/v1/groups/'.rawurlencode($id).'/members',
            body: Params::body([
                'contact_ids' => $contact_ids,
            ]),
            options: $requestOptions,
        );

        return Models\GroupsMembersAddResponse::fromArray($response->json(), $response->meta());
    }

    /**
     * Remove contacts from a group.
     *
     * Requires `groups:write`. 1 to 1,000 contact ids. Ids that are not members count in `not_member`. Each
     * removed contact emits `contact.updated` (changed `groups`).
     *
     * @param  list<string>  $contact_ids
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function remove(
        string $id,
        array $contact_ids,
        ?RequestOptions $requestOptions = null,
    ): Models\GroupsMembersRemoveResponse {
        $response = $this->connection->request(
            'groups.members.remove',
            'POST',
            '/v1/groups/'.rawurlencode($id).'/members/remove',
            body: Params::body([
                'contact_ids' => $contact_ids,
            ]),
            options: $requestOptions,
        );

        return Models\GroupsMembersRemoveResponse::fromArray($response->json(), $response->meta());
    }
}
