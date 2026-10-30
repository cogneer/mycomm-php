<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Resources\Partner;

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
     * Requires `lines:read`. The link without `url`; `status` reads `expired` once `expires_at` has passed. A
     * link of another partner's line is `404 NOT_FOUND`.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function get(
        string $id,
        ?RequestOptions $requestOptions = null,
    ): Models\HostedLink {
        $response = $this->connection->request('partner.links.get', 'GET', '/partner/v1/links/'.rawurlencode($id), options: $requestOptions);

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
        $this->connection->request('partner.links.revoke', 'DELETE', '/partner/v1/links/'.rawurlencode($id), options: $requestOptions);
    }
}
