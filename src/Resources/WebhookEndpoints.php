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

final class WebhookEndpoints
{
    public function __construct(private readonly Connection $connection) {}

    /**
     * List webhook endpoints.
     *
     * Requires `webhooks:write`. Secrets are never returned here. A restricted key sees only endpoints whose
     * `inbox_ids` is set and inside its scope.
     *
     * @param  ?int  $limit  Page size, 1 to 200.
     * @param  ?string  $cursor  Opaque cursor from a previous page's `next_cursor`; valid for 24 hours.
     * @return Page<Models\WebhookEndpoint>
     *
     * @throws InvalidArgumentException when an argument breaks a rule the API documents; nothing is sent (client option `validate`)
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function list(
        ?int $limit = null,
        ?string $cursor = null,
        ?RequestOptions $requestOptions = null,
    ): Page {
        $this->connection->guard(
            'webhookEndpoints.list',
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
            'webhookEndpoints.list',
            'GET',
            '/v1/webhook-endpoints',
            query: Params::query([
                'limit' => $limit,
                'cursor' => $cursor,
            ]),
            options: $requestOptions,
        );

        return Page::fromResponse($response, static fn (array $item): Models\WebhookEndpoint => Models\WebhookEndpoint::fromArray($item), fn (string $next): Page => $this->list(limit: $limit, cursor: $next, requestOptions: $requestOptions));
    }

    /**
     * Every item of `list()`, fetching the following pages as the loop reaches them.
     *
     * `limit` is the page size, not a cap on the items.
     *
     * @param  ?int  $limit  Page size, 1 to 200.
     * @return Generator<int, Models\WebhookEndpoint>
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function all(
        ?int $limit = null,
        ?RequestOptions $requestOptions = null,
    ): Generator {
        return $this->list(limit: $limit, requestOptions: $requestOptions)->autoPaging();
    }

    /**
     * Create a webhook endpoint.
     *
     * Requires `webhooks:write`. The `url` must be `https`, carry no credentials and resolve only to public
     * addresses. `events` empty or omitted means every event except the opt-in ones. `inbox_ids` and
     * `channel_types` `null` mean all. At most 20 endpoints per account. A restricted key's endpoint defaults
     * to (and must stay inside) its inboxes, takes the key's channel types and cannot include managed
     * accounts. The `secret` is shown once, in this response.
     *
     * @param  list<string>|Omit|null  $events
     * @param  list<string>|Omit|null  $inbox_ids
     * @param  list<string>|Omit|null  $channel_types
     * @param  ?bool  $include_managed_accounts  Also receive the events of every managed account. Only on an approved partner's own account, with `inbox_ids` `null`, and never with a restricted key.
     *
     * @throws InvalidArgumentException when an argument breaks a rule the API documents; nothing is sent (client option `validate`)
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function create(
        string $url,
        string|Omit|null $description = Omit::Value,
        array|Omit|null $events = Omit::Value,
        array|Omit|null $inbox_ids = Omit::Value,
        array|Omit|null $channel_types = Omit::Value,
        ?bool $include_managed_accounts = null,
        ?RequestOptions $requestOptions = null,
    ): Models\CreatedWebhookEndpoint {
        $this->connection->guard(
            'webhookEndpoints.create',
            [
                'url' => $url,
                'description' => $description,
                'inbox_ids' => $inbox_ids,
                'channel_types' => $channel_types,
            ],
            [
                'url' => [
                    'maxLength' => 2048,
                ],
                'description' => [
                    'maxLength' => 255,
                ],
                'inbox_ids' => [
                    'minItems' => 1,
                    'maxItems' => 100,
                    'uuid' => true,
                ],
                'channel_types' => [
                    'minItems' => 1,
                ],
            ],
            [],
        );

        $response = $this->connection->request(
            'webhookEndpoints.create',
            'POST',
            '/v1/webhook-endpoints',
            body: Params::body([
                'url' => $url,
                'description' => $description,
                'events' => $events,
                'inbox_ids' => $inbox_ids,
                'channel_types' => $channel_types,
                'include_managed_accounts' => $include_managed_accounts ?? Omit::Value,
            ]),
            options: $requestOptions,
        );

        return Models\CreatedWebhookEndpoint::fromArray($response->json(), $response->meta());
    }

    /**
     * Get a webhook endpoint.
     *
     * Requires `webhooks:write`. Never returns the secret. `404` outside a restricted key's scope.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function get(
        string $id,
        ?RequestOptions $requestOptions = null,
    ): Models\WebhookEndpoint {
        $response = $this->connection->request('webhookEndpoints.get', 'GET', '/v1/webhook-endpoints/'.rawurlencode($id), options: $requestOptions);

        return Models\WebhookEndpoint::fromArray($response->json(), $response->meta());
    }

    /**
     * Update a webhook endpoint.
     *
     * Requires `webhooks:write`. Only the fields sent change. `active: true` re-enables a disabled endpoint
     * and resets its failure counters. An endpoint with `include_managed_accounts` keeps `inbox_ids` `null`.
     *
     * @param  list<string>|Omit|null  $events
     * @param  list<string>|Omit|null  $inbox_ids
     * @param  list<string>|Omit|null  $channel_types
     * @param  ?bool  $include_managed_accounts  Only on an approved partner's own account, with `inbox_ids` `null`, and never with a restricted key.
     *
     * @throws InvalidArgumentException when an argument breaks a rule the API documents; nothing is sent (client option `validate`)
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function update(
        string $id,
        ?string $url = null,
        string|Omit|null $description = Omit::Value,
        array|Omit|null $events = Omit::Value,
        array|Omit|null $inbox_ids = Omit::Value,
        array|Omit|null $channel_types = Omit::Value,
        ?bool $include_managed_accounts = null,
        ?bool $active = null,
        ?RequestOptions $requestOptions = null,
    ): Models\WebhookEndpoint {
        $this->connection->guard(
            'webhookEndpoints.update',
            [
                'url' => $url,
                'description' => $description,
                'inbox_ids' => $inbox_ids,
                'channel_types' => $channel_types,
            ],
            [
                'url' => [
                    'maxLength' => 2048,
                ],
                'description' => [
                    'maxLength' => 255,
                ],
                'inbox_ids' => [
                    'minItems' => 1,
                    'maxItems' => 100,
                    'uuid' => true,
                ],
                'channel_types' => [
                    'minItems' => 1,
                ],
            ],
            [],
        );

        $response = $this->connection->request(
            'webhookEndpoints.update',
            'PATCH',
            '/v1/webhook-endpoints/'.rawurlencode($id),
            body: Params::body([
                'url' => $url ?? Omit::Value,
                'description' => $description,
                'events' => $events,
                'inbox_ids' => $inbox_ids,
                'channel_types' => $channel_types,
                'include_managed_accounts' => $include_managed_accounts ?? Omit::Value,
                'active' => $active ?? Omit::Value,
            ]),
            options: $requestOptions,
        );

        return Models\WebhookEndpoint::fromArray($response->json(), $response->meta());
    }

    /**
     * Delete a webhook endpoint.
     *
     * Requires `webhooks:write`. Pending deliveries are dropped.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function delete(
        string $id,
        ?RequestOptions $requestOptions = null,
    ): void {
        $this->connection->request('webhookEndpoints.delete', 'DELETE', '/v1/webhook-endpoints/'.rawurlencode($id), options: $requestOptions);
    }

    /**
     * Rotate a webhook endpoint's secret.
     *
     * Requires `webhooks:write`. The old secret stays valid as the previous secret for
     * `previous_secret_valid_seconds` (0 to 86400, default 86400); during the overlap every delivery carries
     * both signatures. Rotating again replaces the previous secret.
     *
     * @throws InvalidArgumentException when an argument breaks a rule the API documents; nothing is sent (client option `validate`)
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function rotateSecret(
        string $id,
        ?int $previous_secret_valid_seconds = null,
        ?RequestOptions $requestOptions = null,
    ): Models\RotatedWebhookSecret {
        $this->connection->guard(
            'webhookEndpoints.rotateSecret',
            [
                'previous_secret_valid_seconds' => $previous_secret_valid_seconds,
            ],
            [
                'previous_secret_valid_seconds' => [
                    'min' => 0,
                    'max' => 86400,
                ],
            ],
            [],
        );

        $response = $this->connection->request(
            'webhookEndpoints.rotateSecret',
            'POST',
            '/v1/webhook-endpoints/'.rawurlencode($id).'/rotate-secret',
            body: Params::body([
                'previous_secret_valid_seconds' => $previous_secret_valid_seconds ?? Omit::Value,
            ]),
            options: $requestOptions,
        );

        return Models\RotatedWebhookSecret::fromArray($response->json(), $response->meta());
    }

    /**
     * Send a test ping.
     *
     * Requires `webhooks:write`. Sends a `ping` event now and waits up to 10 s for the answer. Works on
     * disabled endpoints; a failed ping is not retried.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function test(
        string $id,
        ?RequestOptions $requestOptions = null,
    ): Models\WebhookTestResult {
        $response = $this->connection->request('webhookEndpoints.test', 'POST', '/v1/webhook-endpoints/'.rawurlencode($id).'/test', options: $requestOptions);

        return Models\WebhookTestResult::fromArray($response->json(), $response->meta());
    }
}
