<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Resources\Partner;

use MyComm\Exceptions\ApiException;
use MyComm\Exceptions\ConnectionException;
use MyComm\Http\Connection;
use MyComm\Http\Params;
use MyComm\Inputs;
use MyComm\Models;
use MyComm\RequestOptions;

final class SponsorshipDefaults
{
    public function __construct(private readonly Connection $connection) {}

    /**
     * Get your default sponsorship.
     *
     * Requires `lines:read`. The terms a new line gets when `POST /lines` leaves `sponsorship` out; `null` for
     * none.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function get(
        ?RequestOptions $requestOptions = null,
    ): Models\SponsorshipDefaults {
        $response = $this->connection->request('partner.sponsorshipDefaults.get', 'GET', '/partner/v1/sponsorship-defaults', options: $requestOptions);

        return Models\SponsorshipDefaults::fromArray($response->json(), $response->meta());
    }

    /**
     * Set your default sponsorship.
     *
     * Requires `lines:write`. Replaces the terms new lines get when `POST /lines` leaves `sponsorship` out;
     * `null` removes them. Lines that already exist keep their own terms. Wrong terms answer `422
     * VALIDATION_FAILED` naming the field, for example `default_sponsorship.message_allowance.fax`.
     *
     * @param  Inputs\SponsorshipTerms|array<string, mixed>|null  $default_sponsorship
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function put(
        Inputs\SponsorshipTerms|array|null $default_sponsorship,
        ?RequestOptions $requestOptions = null,
    ): Models\SponsorshipDefaults {
        $response = $this->connection->request(
            'partner.sponsorshipDefaults.put',
            'PUT',
            '/partner/v1/sponsorship-defaults',
            body: Params::body([
                'default_sponsorship' => Params::object($default_sponsorship),
            ]),
            options: $requestOptions,
        );

        return Models\SponsorshipDefaults::fromArray($response->json(), $response->meta());
    }
}
