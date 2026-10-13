<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Resources;

use Generator;
use MyComm\Exceptions\ApiException;
use MyComm\Exceptions\ConnectionException;
use MyComm\Http\Connection;
use MyComm\Http\Params;
use MyComm\Models;
use MyComm\Omit;
use MyComm\Page;
use MyComm\RequestOptions;

final class Inboxes
{
    public function __construct(private readonly Connection $connection) {}

    /**
     * List inboxes.
     *
     * Requires `channels:read`. Archived inboxes are hidden unless `archived=true`. A key restricted to
     * inboxes sees only those.
     *
     * @param  ?string  $channel_type  One of: `android_sim`, `whatsapp_evolution`, `telegram_bot`.
     * @param  ?string  $kind  One of: `main`, `integration`.
     * @param  ?bool  $archived  Default `false`.
     * @param  ?int  $limit  Page size, 1 to 200.
     * @param  ?string  $cursor  Opaque cursor from a previous page's `next_cursor`; valid for 24 hours.
     * @return Page<Models\Inbox>
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function list(
        ?string $channel_id = null,
        ?string $channel_type = null,
        ?string $kind = null,
        ?bool $archived = null,
        ?int $limit = null,
        ?string $cursor = null,
        ?RequestOptions $requestOptions = null,
    ): Page {
        $response = $this->connection->request(
            'inboxes.list',
            'GET',
            '/v1/inboxes',
            query: Params::query([
                'channel_id' => $channel_id,
                'channel_type' => $channel_type,
                'kind' => $kind,
                'archived' => $archived,
                'limit' => $limit,
                'cursor' => $cursor,
            ]),
            options: $requestOptions,
        );

        return Page::fromResponse($response, static fn (array $item): Models\Inbox => Models\Inbox::fromArray($item), fn (string $next): Page => $this->list(channel_id: $channel_id, channel_type: $channel_type, kind: $kind, archived: $archived, limit: $limit, cursor: $next, requestOptions: $requestOptions));
    }

    /**
     * Every item of `list()`, fetching the following pages as the loop reaches them.
     *
     * `limit` is the page size, not a cap on the items.
     *
     * @param  ?string  $channel_type  One of: `android_sim`, `whatsapp_evolution`, `telegram_bot`.
     * @param  ?string  $kind  One of: `main`, `integration`.
     * @param  ?bool  $archived  Default `false`.
     * @param  ?int  $limit  Page size, 1 to 200.
     * @return Generator<int, Models\Inbox>
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function all(
        ?string $channel_id = null,
        ?string $channel_type = null,
        ?string $kind = null,
        ?bool $archived = null,
        ?int $limit = null,
        ?RequestOptions $requestOptions = null,
    ): Generator {
        return $this->list(channel_id: $channel_id, channel_type: $channel_type, kind: $kind, archived: $archived, limit: $limit, requestOptions: $requestOptions)->autoPaging();
    }

    /**
     * Create an integration inbox.
     *
     * Requires `channels:write`. Adds an `integration` inbox to a channel. `404` when the channel is unknown
     * or outside the key's scope; `409 NAME_TAKEN` `{field: "name"}` when the name is used on the channel
     * (case-insensitive).
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function create(
        string $channel_id,
        string $name,
        ?RequestOptions $requestOptions = null,
    ): Models\Inbox {
        $response = $this->connection->request(
            'inboxes.create',
            'POST',
            '/v1/inboxes',
            body: Params::body([
                'channel_id' => $channel_id,
                'name' => $name,
            ]),
            options: $requestOptions,
        );

        return Models\Inbox::fromArray($response->json(), $response->meta());
    }

    /**
     * Archive an integration inbox.
     *
     * Requires `channels:write`. Integration inboxes only (`409 INVALID_STATE` for a `main` inbox). Sends from
     * it are refused (`INBOX_ARCHIVED`) and reply routing skips it. Keys restricted to it keep read access.
     * Archiving an archived inbox changes nothing.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function archive(
        string $id,
        ?RequestOptions $requestOptions = null,
    ): Models\Inbox {
        $response = $this->connection->request('inboxes.archive', 'POST', '/v1/inboxes/'.rawurlencode($id).'/archive', options: $requestOptions);

        return Models\Inbox::fromArray($response->json(), $response->meta());
    }

    /**
     * Unarchive an inbox.
     *
     * Requires `channels:write`. Does nothing when the inbox is not archived.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function unarchive(
        string $id,
        ?RequestOptions $requestOptions = null,
    ): Models\Inbox {
        $response = $this->connection->request('inboxes.unarchive', 'POST', '/v1/inboxes/'.rawurlencode($id).'/unarchive', options: $requestOptions);

        return Models\Inbox::fromArray($response->json(), $response->meta());
    }

    /**
     * Get an inbox.
     *
     * Requires `channels:read`. `404` when the inbox is outside the key's scope.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function get(
        string $id,
        ?RequestOptions $requestOptions = null,
    ): Models\Inbox {
        $response = $this->connection->request('inboxes.get', 'GET', '/v1/inboxes/'.rawurlencode($id), options: $requestOptions);

        return Models\Inbox::fromArray($response->json(), $response->meta());
    }

    /**
     * Update an inbox.
     *
     * Requires `channels:write`. `settings` keys are merged into the stored settings (a key set to `null` is
     * removed). `409 NAME_TAKEN` `{field: "name"}` when the name is used on the channel (case-insensitive).
     *
     * @param  array<string, mixed>|null  $settings  Merged key by key into the inbox settings; `null` removes a key.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function update(
        string $id,
        ?string $name = null,
        ?array $settings = null,
        ?RequestOptions $requestOptions = null,
    ): Models\Inbox {
        $response = $this->connection->request(
            'inboxes.update',
            'PATCH',
            '/v1/inboxes/'.rawurlencode($id),
            body: Params::body([
                'name' => $name ?? Omit::Value,
                'settings' => Params::object($settings ?? Omit::Value),
            ]),
            options: $requestOptions,
        );

        return Models\Inbox::fromArray($response->json(), $response->meta());
    }
}
