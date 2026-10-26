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

final class Devices
{
    public readonly Devices\Sims $sims;

    public function __construct(private readonly Connection $connection)
    {
        $this->sims = new Devices\Sims($connection);
    }

    /**
     * List devices.
     *
     * Requires `channels:read`. Returns `active` devices unless `status` says otherwise. A key restricted to
     * inboxes sees only the devices of its channels.
     *
     * @param  ?string  $status  Default `active`. One of: `active`, `revoked`.
     * @param  ?string  $connection_state  One of: `online`, `degraded`, `offline`.
     * @param  ?int  $limit  Page size, 1 to 200.
     * @param  ?string  $cursor  Opaque cursor from a previous page's `next_cursor`; valid for 24 hours.
     * @return Page<Models\Device>
     *
     * @throws InvalidArgumentException when an argument breaks a rule the API documents; nothing is sent (client option `validate`)
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function list(
        ?string $status = null,
        ?string $connection_state = null,
        ?int $limit = null,
        ?string $cursor = null,
        ?RequestOptions $requestOptions = null,
    ): Page {
        $this->connection->guard(
            'devices.list',
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
            'devices.list',
            'GET',
            '/v1/devices',
            query: Params::query([
                'status' => $status,
                'connection_state' => $connection_state,
                'limit' => $limit,
                'cursor' => $cursor,
            ]),
            options: $requestOptions,
        );

        return Page::fromResponse($response, static fn (array $item): Models\Device => Models\Device::fromArray($item), fn (string $next): Page => $this->list(status: $status, connection_state: $connection_state, limit: $limit, cursor: $next, requestOptions: $requestOptions));
    }

    /**
     * Every item of `list()`, fetching the following pages as the loop reaches them.
     *
     * `limit` is the page size, not a cap on the items.
     *
     * @param  ?string  $status  Default `active`. One of: `active`, `revoked`.
     * @param  ?string  $connection_state  One of: `online`, `degraded`, `offline`.
     * @param  ?int  $limit  Page size, 1 to 200.
     * @return Generator<int, Models\Device>
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function all(
        ?string $status = null,
        ?string $connection_state = null,
        ?int $limit = null,
        ?RequestOptions $requestOptions = null,
    ): Generator {
        return $this->list(status: $status, connection_state: $connection_state, limit: $limit, requestOptions: $requestOptions)->autoPaging();
    }

    /**
     * Get a device.
     *
     * Requires `channels:read`. `404` when the device is outside the key's scope.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function get(
        string $id,
        ?RequestOptions $requestOptions = null,
    ): Models\Device {
        $response = $this->connection->request('devices.get', 'GET', '/v1/devices/'.rawurlencode($id), options: $requestOptions);

        return Models\Device::fromArray($response->json(), $response->meta());
    }

    /**
     * Rename a device or record its OS-limit raise.
     *
     * Requires `channels:write`; a key restricted to inboxes never holds it. Changes only the keys sent.
     * `name` is 1 to 60 characters. `os_limit_raised: true` records the owner's confirmation that the Android
     * SMS limit was raised with ADB on this phone and needs `os_limit_value` (31 to 100,000); `false` clears
     * the value. Either change sets `os_limit_changed_at` (the later write wins over a confirmation from the
     * app), is audit-logged as `device.os_limit.updated` and applies to the pacing of the device's channels at
     * once. A revoked device is `409 INVALID_STATE`. `404` when the device is outside the key's scope.
     *
     * @param  ?bool  $os_limit_raised  Required when `os_limit_value` is sent.
     * @param  int|Omit|null  $os_limit_value  Required when `os_limit_raised` is `true`; ignored and cleared when it is `false`.
     *
     * @throws InvalidArgumentException when an argument breaks a rule the API documents; nothing is sent (client option `validate`)
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function update(
        string $id,
        ?string $name = null,
        ?bool $os_limit_raised = null,
        int|Omit|null $os_limit_value = Omit::Value,
        ?RequestOptions $requestOptions = null,
    ): Models\Device {
        $this->connection->guard(
            'devices.update',
            [
                'name' => $name,
                'os_limit_value' => $os_limit_value,
                'os_limit_raised' => $os_limit_raised,
            ],
            [
                'name' => [
                    'minLength' => 1,
                    'maxLength' => 60,
                ],
                'os_limit_value' => [
                    'min' => 31,
                    'max' => 100000,
                ],
            ],
            [
                ['requires', 'os_limit_value', 'os_limit_raised'],
                ['requiredWhen', 'os_limit_value', 'os_limit_raised', true],
            ],
        );

        $response = $this->connection->request(
            'devices.update',
            'PATCH',
            '/v1/devices/'.rawurlencode($id),
            body: Params::body([
                'name' => $name ?? Omit::Value,
                'os_limit_raised' => $os_limit_raised ?? Omit::Value,
                'os_limit_value' => $os_limit_value,
            ]),
            options: $requestOptions,
        );

        return Models\Device::fromArray($response->json(), $response->meta());
    }

    /**
     * Pause a device.
     *
     * Requires `channels:write`. The device gets no further leases (`paused: true`, `pause_reason: owner`);
     * the app releases its buffered messages and the messages of its channels wait with `hold_reason:
     * device_paused`. A device already paused keeps its reason. A revoked device is `409 INVALID_STATE`.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function pause(
        string $id,
        ?RequestOptions $requestOptions = null,
    ): Models\Device {
        $response = $this->connection->request('devices.pause', 'POST', '/v1/devices/'.rawurlencode($id).'/pause', options: $requestOptions);

        return Models\Device::fromArray($response->json(), $response->meta());
    }

    /**
     * Resume a paused device.
     *
     * Requires `channels:write`. Ends a pause with `pause_reason: owner`; an `admin` pause ends only through
     * MyComm and an `account_suspended` pause only when the account is reactivated (`409 INVALID_STATE`). A
     * device that is not paused is returned unchanged.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function resume(
        string $id,
        ?RequestOptions $requestOptions = null,
    ): Models\Device {
        $response = $this->connection->request('devices.resume', 'POST', '/v1/devices/'.rawurlencode($id).'/resume', options: $requestOptions);

        return Models\Device::fromArray($response->json(), $response->meta());
    }

    /**
     * Revoke (unpair) a device.
     *
     * Requires `channels:write`. `status: revoked` with `revoked_reason: owner` (`partner` when a partner key
     * calls it). Its channels become `paused` with `pause_reason: device_revoked`. Messages the phone holds
     * are not re-queued: their leases are orphaned and the phone may still report them until `drain_until` (=
     * `revoked_at` + 24 h); any still unresolved then fail with `UNCONFIRMED`. The phone gets no further
     * leases, its next heartbeat carries an `unpair` action, and after `drain_until` every device call gets
     * `410 DEVICE_REVOKED`. Emits `device.revoked`. Revoking a revoked device returns it unchanged.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function revoke(
        string $id,
        ?RequestOptions $requestOptions = null,
    ): Models\Device {
        $response = $this->connection->request('devices.revoke', 'POST', '/v1/devices/'.rawurlencode($id).'/revoke', options: $requestOptions);

        return Models\Device::fromArray($response->json(), $response->meta());
    }
}
