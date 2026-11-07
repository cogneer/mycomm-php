<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Resources;

use MyComm\Exceptions\ApiException;
use MyComm\Exceptions\ConnectionException;
use MyComm\Http\Connection;
use MyComm\Http\Params;
use MyComm\Models;
use MyComm\Omit;
use MyComm\RequestOptions;

final class Me
{
    public function __construct(private readonly Connection $connection) {}

    /**
     * Get the user.
     *
     * The user the key belongs to. Needs no ability and also works for a suspended user. `settings` is the
     * user's settings without `notifications` and `onboarding`, which concern the portal only.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function get(
        ?RequestOptions $requestOptions = null,
    ): Models\User {
        $response = $this->connection->request('me.get', 'GET', '/v1/me', options: $requestOptions);

        return Models\User::fromArray($response->json(), $response->meta());
    }

    /**
     * Update the user.
     *
     * Requires `me:write`. `settings` is deep-merged: arrays are replaced whole, language maps merge key by
     * key and `null` removes a language. `settings.limits` is read-only for user keys (`422
     * VALIDATION_FAILED`). Changes are audit-logged.
     *
     * @param  array<string, mixed>|null  $settings  Deep-merged into the user's settings; `limits` is refused.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function update(
        ?string $name = null,
        ?string $country_iso = null,
        ?string $timezone = null,
        ?string $default_locale = null,
        ?array $settings = null,
        ?RequestOptions $requestOptions = null,
    ): Models\User {
        $response = $this->connection->request(
            'me.update',
            'PATCH',
            '/v1/me',
            body: Params::body([
                'name' => $name ?? Omit::Value,
                'country_iso' => $country_iso ?? Omit::Value,
                'timezone' => $timezone ?? Omit::Value,
                'default_locale' => $default_locale ?? Omit::Value,
                'settings' => Params::object($settings ?? Omit::Value),
            ]),
            options: $requestOptions,
        );

        return Models\User::fromArray($response->json(), $response->meta());
    }
}
