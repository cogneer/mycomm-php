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

final class PairingCodes
{
    public function __construct(private readonly Connection $connection)
    {
    }

    /**
     * Create a pairing code.
     *
     * Requires `channels:write`. The code is 8 Crockford base32 characters, single use and valid for 10
     * minutes. At most 20 codes are pending and 60 are created per account per hour (`422
     * PAIRING_CODE_LIMIT_REACHED` with `retry_after_seconds`). Render `qr_payload` as-is as a QR code.
     *
     * @param  string|Omit|null  $device_name  Becomes the name of the device when it pairs.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function create(
        string|Omit|null $device_name = Omit::Value,
        ?RequestOptions $requestOptions = null,
    ): Models\PairingCode {
        $response = $this->connection->request(
            'pairingCodes.create',
            'POST',
            '/v1/pairing-codes',
            body: Params::body([
                'device_name' => $device_name,
            ]),
            options: $requestOptions,
        );

        return Models\PairingCode::fromArray($response->json(), $response->meta());
    }

    /**
     * Get a pairing code.
     *
     * Requires `channels:read`. Poll every 3 to 5 seconds while the QR is displayed, or wait for
     * `device.paired`. `code` and `qr_payload` are `null` here because the code is stored hashed.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function get(
        string $id,
        ?RequestOptions $requestOptions = null,
    ): Models\PairingCode {
        $response = $this->connection->request('pairingCodes.get', 'GET', '/v1/pairing-codes/'.rawurlencode($id), options: $requestOptions);

        return Models\PairingCode::fromArray($response->json(), $response->meta());
    }

    /**
     * Invalidate a pairing code.
     *
     * Requires `channels:write`. Only a `pending` code can be invalidated; any other status is `409
     * INVALID_STATE`.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function delete(
        string $id,
        ?RequestOptions $requestOptions = null,
    ): void {
        $this->connection->request('pairingCodes.delete', 'DELETE', '/v1/pairing-codes/'.rawurlencode($id), options: $requestOptions);
    }
}
