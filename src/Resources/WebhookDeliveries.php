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

final class WebhookDeliveries
{
    public function __construct(private readonly Connection $connection)
    {
    }

    /**
     * List an endpoint's deliveries.
     *
     * Requires `webhooks:write`. Newest first. Filters `status` (`pending`, `failed`, `delivered`, `dead`;
     * comma-separated for several), `event_type`, `event_id`, `created_after` (inclusive) and `created_before`
     * (exclusive). An endpoint outside the caller's scope answers `404 NOT_FOUND`.
     *
     * @param  ?int  $limit  Page size, 1 to 200.
     * @param  ?string  $cursor  Opaque cursor from a previous page's `next_cursor`; valid for 24 hours.
     * @return Page<Models\WebhookDelivery>
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function list(
        string $id,
        ?string $status = null,
        ?string $event_type = null,
        ?string $event_id = null,
        DateTimeInterface|string|null $created_after = null,
        DateTimeInterface|string|null $created_before = null,
        ?int $limit = null,
        ?string $cursor = null,
        ?RequestOptions $requestOptions = null,
    ): Page {
        $response = $this->connection->request(
            'webhookDeliveries.list',
            'GET',
            '/v1/webhook-endpoints/'.rawurlencode($id).'/deliveries',
            query: Params::query([
                'status' => $status,
                'event_type' => $event_type,
                'event_id' => $event_id,
                'created_after' => $created_after,
                'created_before' => $created_before,
                'limit' => $limit,
                'cursor' => $cursor,
            ]),
            options: $requestOptions,
        );

        return Page::fromResponse($response, static fn (array $item): Models\WebhookDelivery => Models\WebhookDelivery::fromArray($item), fn (string $next): Page => $this->list(id: $id, status: $status, event_type: $event_type, event_id: $event_id, created_after: $created_after, created_before: $created_before, limit: $limit, cursor: $next, requestOptions: $requestOptions));
    }

    /**
     * Every item of `list()`, fetching the following pages as the loop reaches them.
     *
     * `limit` is the page size, not a cap on the items.
     *
     * @param  ?int  $limit  Page size, 1 to 200.
     * @return Generator<int, Models\WebhookDelivery>
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function all(
        string $id,
        ?string $status = null,
        ?string $event_type = null,
        ?string $event_id = null,
        DateTimeInterface|string|null $created_after = null,
        DateTimeInterface|string|null $created_before = null,
        ?int $limit = null,
        ?RequestOptions $requestOptions = null,
    ): Generator {
        return $this->list(id: $id, status: $status, event_type: $event_type, event_id: $event_id, created_after: $created_after, created_before: $created_before, limit: $limit, requestOptions: $requestOptions)->autoPaging();
    }

    /**
     * Bulk replay an endpoint's deliveries.
     *
     * Requires `webhooks:write`. Replays, oldest first and at most 10,000 per call, every delivery of the
     * endpoint in `status` (default `dead`) created inside the optional window. Deliveries whose payload was
     * removed after 30 days are skipped.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function bulkReplay(
        string $id,
        Enums\WebhookDeliveriesBulkReplayStatus|string|null $status = null,
        DateTimeInterface|string|null $created_after = null,
        DateTimeInterface|string|null $created_before = null,
        ?RequestOptions $requestOptions = null,
    ): Models\WebhookDeliveriesBulkReplayResponse {
        $response = $this->connection->request(
            'webhookDeliveries.bulkReplay',
            'POST',
            '/v1/webhook-endpoints/'.rawurlencode($id).'/replay',
            body: Params::body([
                'status' => $status ?? Omit::Value,
                'created_after' => $created_after ?? Omit::Value,
                'created_before' => $created_before ?? Omit::Value,
            ]),
            options: $requestOptions,
        );

        return Models\WebhookDeliveriesBulkReplayResponse::fromArray($response->json(), $response->meta());
    }

    /**
     * Get a delivery.
     *
     * Requires `webhooks:write`. `payload` is the exact signed body (`null` after 30 days);
     * `last_response_excerpt` is the first 4 KB of the receiver's last response body.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function get(
        string $id,
        ?RequestOptions $requestOptions = null,
    ): Models\WebhookDelivery {
        $response = $this->connection->request('webhookDeliveries.get', 'GET', '/v1/webhook-deliveries/'.rawurlencode($id), options: $requestOptions);

        return Models\WebhookDelivery::fromArray($response->json(), $response->meta());
    }

    /**
     * Replay a delivery.
     *
     * Requires `webhooks:write`. Re-sends the stored payload now with a fresh signature and timestamp and the
     * same event `id`; status goes back to `pending` and attempts reset. `409 INVALID_STATE` when the payload
     * was removed after 30 days.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function replay(
        string $id,
        ?RequestOptions $requestOptions = null,
    ): Models\WebhookDelivery {
        $response = $this->connection->request('webhookDeliveries.replay', 'POST', '/v1/webhook-deliveries/'.rawurlencode($id).'/replay', options: $requestOptions);

        return Models\WebhookDelivery::fromArray($response->json(), $response->meta());
    }
}
