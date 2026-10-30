<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Resources;

use MyComm\Exceptions\ApiException;
use MyComm\Exceptions\ConnectionException;
use MyComm\Http\Connection;
use MyComm\Models;
use MyComm\Page;
use MyComm\RequestOptions;

final class Links
{
    public function __construct(private readonly Connection $connection) {}

    /**
     * Get a hosted link.
     *
     * Requires `channels:read`. The link without `url`; `status` reads `expired` once `expires_at` has passed.
     * A link of a line this key does not see is `404 NOT_FOUND`.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function get(
        string $id,
        ?RequestOptions $requestOptions = null,
    ): Models\HostedLink {
        $response = $this->connection->request('links.get', 'GET', '/v1/links/'.rawurlencode($id), options: $requestOptions);

        return Models\HostedLink::fromArray($response->json(), $response->meta());
    }

    /**
     * Revoke a hosted link.
     *
     * Requires `links:write`. The link stops working at once, also for a page already open. Only an `open`
     * link can be revoked; any other status is `409 INVALID_STATE` with `details.status`.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function revoke(
        string $id,
        ?RequestOptions $requestOptions = null,
    ): void {
        $this->connection->request('links.revoke', 'DELETE', '/v1/links/'.rawurlencode($id), options: $requestOptions);
    }
}
