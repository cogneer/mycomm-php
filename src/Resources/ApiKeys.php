<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Resources;

use DateTimeInterface;
use Generator;
use MyComm\Exceptions\ApiException;
use MyComm\Exceptions\ConnectionException;
use MyComm\Http\Connection;
use MyComm\Http\Params;
use MyComm\Models;
use MyComm\Omit;
use MyComm\Page;
use MyComm\RequestOptions;

final class ApiKeys
{
    public function __construct(private readonly Connection $connection) {}

    /**
     * List API keys.
     *
     * Requires `keys:write`. Revoked keys are hidden unless `revoked=true`. The token is never returned here.
     *
     * @param  ?string  $inbox_id  Keys whose scope lists this inbox.
     * @param  ?bool  $restricted  `true` for keys limited to inboxes or channel types, `false` for unrestricted keys.
     * @param  ?bool  $revoked  Default `false`.
     * @param  ?int  $limit  Page size, 1 to 200.
     * @param  ?string  $cursor  Opaque cursor from a previous page's `next_cursor`; valid for 24 hours.
     * @return Page<Models\ApiKey>
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function list(
        ?string $inbox_id = null,
        ?bool $restricted = null,
        ?bool $revoked = null,
        ?int $limit = null,
        ?string $cursor = null,
        ?RequestOptions $requestOptions = null,
    ): Page {
        $response = $this->connection->request(
            'apiKeys.list',
            'GET',
            '/v1/api-keys',
            query: Params::query([
                'inbox_id' => $inbox_id,
                'restricted' => $restricted,
                'revoked' => $revoked,
                'limit' => $limit,
                'cursor' => $cursor,
            ]),
            options: $requestOptions,
        );

        return Page::fromResponse($response, static fn (array $item): Models\ApiKey => Models\ApiKey::fromArray($item), fn (string $next): Page => $this->list(inbox_id: $inbox_id, restricted: $restricted, revoked: $revoked, limit: $limit, cursor: $next, requestOptions: $requestOptions));
    }

    /**
     * Every item of `list()`, fetching the following pages as the loop reaches them.
     *
     * `limit` is the page size, not a cap on the items.
     *
     * @param  ?string  $inbox_id  Keys whose scope lists this inbox.
     * @param  ?bool  $restricted  `true` for keys limited to inboxes or channel types, `false` for unrestricted keys.
     * @param  ?bool  $revoked  Default `false`.
     * @param  ?int  $limit  Page size, 1 to 200.
     * @return Generator<int, Models\ApiKey>
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function all(
        ?string $inbox_id = null,
        ?bool $restricted = null,
        ?bool $revoked = null,
        ?int $limit = null,
        ?RequestOptions $requestOptions = null,
    ): Generator {
        return $this->list(inbox_id: $inbox_id, restricted: $restricted, revoked: $revoked, limit: $limit, requestOptions: $requestOptions)->autoPaging();
    }

    /**
     * Create an API key.
     *
     * Requires `keys:write`. Abilities default per 03 section 2.2, must be a subset of the calling key's, and
     * a restricted key cannot hold the abilities marked with a cross there. A restricted caller can create
     * keys only inside its own scope. `partner` keys are never created here. The `token` is shown once, in
     * this response.
     *
     * @param  list<string>|Omit|null  $inbox_ids  `null` or omitted for all inboxes.
     * @param  list<string>|Omit|null  $channel_types  `null` or omitted for all channel types, for example `["android_sim"]`.
     * @param  list<string>|Omit|null  $abilities
     * @param  list<string>|Omit|null  $allowed_ips  IPv4/IPv6 addresses or CIDR ranges.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function create(
        string $name,
        array|Omit|null $inbox_ids = Omit::Value,
        array|Omit|null $channel_types = Omit::Value,
        array|Omit|null $abilities = Omit::Value,
        array|Omit|null $allowed_ips = Omit::Value,
        DateTimeInterface|string|Omit|null $expires_at = Omit::Value,
        ?RequestOptions $requestOptions = null,
    ): Models\CreatedApiKey {
        $response = $this->connection->request(
            'apiKeys.create',
            'POST',
            '/v1/api-keys',
            body: Params::body([
                'name' => $name,
                'inbox_ids' => $inbox_ids,
                'channel_types' => $channel_types,
                'abilities' => $abilities,
                'allowed_ips' => $allowed_ips,
                'expires_at' => $expires_at,
            ]),
            options: $requestOptions,
        );

        return Models\CreatedApiKey::fromArray($response->json(), $response->meta());
    }

    /**
     * Revoke an API key.
     *
     * Requires `keys:write`. Revokes immediately; the key's next call is `401`. Revoking a revoked key is a
     * no-op.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function revoke(
        string $id,
        ?RequestOptions $requestOptions = null,
    ): void {
        $this->connection->request('apiKeys.revoke', 'DELETE', '/v1/api-keys/'.rawurlencode($id), options: $requestOptions);
    }
}
