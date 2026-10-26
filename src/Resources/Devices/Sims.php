<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Resources\Devices;

use MyComm\Exceptions\ApiException;
use MyComm\Exceptions\ConnectionException;
use MyComm\Http\Connection;
use MyComm\Models;
use MyComm\Page;
use MyComm\RequestOptions;

final class Sims
{
    public function __construct(private readonly Connection $connection) {}

    /**
     * List the SIMs of a device.
     *
     * Requires `channels:read`. Every SIM row of the device, not paginated. `404` when the device is outside
     * the key's scope.
     *
     * @return Page<Models\DeviceSim>
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function list(
        string $id,
        ?RequestOptions $requestOptions = null,
    ): Page {
        $response = $this->connection->request('devices.sims.list', 'GET', '/v1/devices/'.rawurlencode($id).'/sims', options: $requestOptions);

        return Page::fromResponse($response, static fn (array $item): Models\DeviceSim => Models\DeviceSim::fromArray($item), null);
    }
}
