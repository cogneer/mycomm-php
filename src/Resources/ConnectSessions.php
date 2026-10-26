<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Resources;

use MyComm\Exceptions\ApiException;
use MyComm\Exceptions\ConnectionException;
use MyComm\Exceptions\InvalidArgumentException;
use MyComm\Http\Connection;
use MyComm\Http\Params;
use MyComm\Models;
use MyComm\Omit;
use MyComm\Page;
use MyComm\RequestOptions;

final class ConnectSessions
{
    public function __construct(private readonly Connection $connection) {}

    /**
     * Create a connect session.
     *
     * Requires `channels:write`. Send your tenant's browser to `url` to connect a phone on MyComm's hosted
     * page without an API key in the browser. Works with an account key and with a partner key acting as a
     * managed account (`MyComm-Account`). `url` is returned only here and is a bearer credential: treat it
     * like a password. At most 20 sessions are open per account (`422 CONNECT_SESSION_LIMIT_REACHED` with
     * `details.max`) and 60 are created per account per hour (`429 RATE_LIMITED`).
     *
     * @param  string|Omit|null  $return_url  `https` URL the page returns to when done; no credentials.
     * @param  list<string>|Omit|null  $allowed_origins  Origins that may frame the page and receive its outcome. `null` = no framing and no messages.
     * @param  int|Omit|null  $expires_in  Seconds until the link stops working.
     * @param  string|Omit|null  $locale  BCP 47 tag of the page's language; defaults to the account's default locale.
     *
     * @throws InvalidArgumentException when an argument breaks a rule the API documents; nothing is sent (client option `validate`)
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function create(
        string|Omit|null $return_url = Omit::Value,
        array|Omit|null $allowed_origins = Omit::Value,
        int|Omit|null $expires_in = Omit::Value,
        string|Omit|null $locale = Omit::Value,
        ?RequestOptions $requestOptions = null,
    ): Models\ConnectSessionsCreateResponse {
        $this->connection->guard(
            'connectSessions.create',
            [
                'return_url' => $return_url,
                'allowed_origins' => $allowed_origins,
                'expires_in' => $expires_in,
                'locale' => $locale,
            ],
            [
                'return_url' => [
                    'maxLength' => 2048,
                ],
                'allowed_origins' => [
                    'minItems' => 1,
                    'maxItems' => 5,
                ],
                'expires_in' => [
                    'min' => 300,
                    'max' => 3600,
                ],
                'locale' => [
                    'maxLength' => 35,
                ],
            ],
            [],
        );

        $response = $this->connection->request(
            'connectSessions.create',
            'POST',
            '/v1/connect-sessions',
            body: Params::body([
                'return_url' => $return_url,
                'allowed_origins' => $allowed_origins,
                'expires_in' => $expires_in,
                'locale' => $locale,
            ]),
            options: $requestOptions,
        );

        return Models\ConnectSessionsCreateResponse::fromArray($response->json(), $response->meta());
    }

    /**
     * Get a connect session.
     *
     * Requires `channels:read`. The session without `url`; `status` reads `expired` once `expires_at` has
     * passed.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function get(
        string $id,
        ?RequestOptions $requestOptions = null,
    ): Models\ConnectSession {
        $response = $this->connection->request('connectSessions.get', 'GET', '/v1/connect-sessions/'.rawurlencode($id), options: $requestOptions);

        return Models\ConnectSession::fromArray($response->json(), $response->meta());
    }

    /**
     * Revoke a connect session.
     *
     * Requires `channels:write`. The link stops working at once, also for a page already open. Only an `open`
     * session can be revoked; any other status is `409 INVALID_STATE` with `details.status`.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function delete(
        string $id,
        ?RequestOptions $requestOptions = null,
    ): void {
        $this->connection->request('connectSessions.delete', 'DELETE', '/v1/connect-sessions/'.rawurlencode($id), options: $requestOptions);
    }
}
