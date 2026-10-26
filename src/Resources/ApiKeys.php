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
     * @throws InvalidArgumentException when an argument breaks a rule the API documents; nothing is sent (client option `validate`)
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
        $this->connection->guard(
            'apiKeys.list',
            [
                'inbox_id' => $inbox_id,
                'limit' => $limit,
            ],
            [
                'inbox_id' => [
                    'uuid' => true,
                ],
                'limit' => [
                    'min' => 1,
                    'max' => 200,
                ],
            ],
            [],
        );

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
     * Requires `keys:write`. Abilities default to the preset for the key's scope, must be a subset of the
     * calling key's, and a restricted key cannot hold the abilities marked with a cross there. A restricted
     * caller can create keys only inside its own scope. `partner` keys are never created here. The `token` is
     * shown once, in this response.
     *
     * @param  list<string>|Omit|null  $inbox_ids  `null` or omitted for all inboxes.
     * @param  list<string>|Omit|null  $channel_types  `null` or omitted for all channel types, for example `["android_sim"]`.
     * @param  list<string>|Omit|null  $abilities
     * @param  list<string>|Omit|null  $allowed_ips  IPv4/IPv6 addresses or CIDR ranges.
     *
     * @throws InvalidArgumentException when an argument breaks a rule the API documents; nothing is sent (client option `validate`)
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
        $this->connection->guard(
            'apiKeys.create',
            [
                'name' => $name,
                'inbox_ids' => $inbox_ids,
                'channel_types' => $channel_types,
                'abilities' => $abilities,
                'allowed_ips' => $allowed_ips,
            ],
            [
                'name' => [
                    'minLength' => 1,
                    'maxLength' => 100,
                ],
                'inbox_ids' => [
                    'minItems' => 1,
                    'maxItems' => 100,
                    'uuid' => true,
                ],
                'channel_types' => [
                    'minItems' => 1,
                ],
                'abilities' => [
                    'minItems' => 1,
                ],
                'allowed_ips' => [
                    'minItems' => 1,
                    'maxItems' => 20,
                ],
            ],
            [],
        );

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

    /**
     * Rotate an API key.
     *
     * Requires `keys:write`. Creates a new key with the same name, scope, abilities, allowed IPs and expiry,
     * and returns its `token` once. The old key keeps working for `grace_period_hours` (default 24, at most
     * 168) so you can deploy the new token without a gap; with `0` it is revoked at once. The old key's expiry
     * never moves later than it already was. A restricted caller can rotate only keys inside its own scope. A
     * revoked or expired key cannot be rotated.
     *
     * @throws InvalidArgumentException when an argument breaks a rule the API documents; nothing is sent (client option `validate`)
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function rotate(
        string $id,
        ?int $grace_period_hours = null,
        ?RequestOptions $requestOptions = null,
    ): Models\ApiKeysRotateResponse {
        $this->connection->guard(
            'apiKeys.rotate',
            [
                'grace_period_hours' => $grace_period_hours,
            ],
            [
                'grace_period_hours' => [
                    'min' => 0,
                    'max' => 168,
                ],
            ],
            [],
        );

        $response = $this->connection->request(
            'apiKeys.rotate',
            'POST',
            '/v1/api-keys/'.rawurlencode($id).'/rotate',
            body: Params::body([
                'grace_period_hours' => $grace_period_hours ?? Omit::Value,
            ]),
            options: $requestOptions,
        );

        return Models\ApiKeysRotateResponse::fromArray($response->json(), $response->meta());
    }
}
