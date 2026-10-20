<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Resources\SmartLists;

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

final class Members
{
    public function __construct(private readonly Connection $connection)
    {
    }

    /**
     * List the contacts of a smart list.
     *
     * Requires `contacts:read`. The contacts the filter matches now (evaluated live, never cached) with the
     * filters, sorts and resource of `GET /v1/contacts`. A saved filter that no longer validates (for example
     * a group it names was deleted) answers `422 FILTER_INVALID` until the list is edited.
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
            'smartLists.members.list',
            'GET',
            '/v1/smart-lists/'.rawurlencode($id).'/members',
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
}
