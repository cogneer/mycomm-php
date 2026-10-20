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

final class Account
{
    public function __construct(private readonly Connection $connection)
    {
    }

    /**
     * Get the account.
     *
     * The account the key belongs to. Needs no ability and also works for a suspended account. `settings` is
     * `accounts.settings` without `notifications` and `onboarding`, which concern portal users only.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function get(
        ?RequestOptions $requestOptions = null,
    ): Models\Account {
        $response = $this->connection->request('account.get', 'GET', '/v1/account', options: $requestOptions);

        return Models\Account::fromArray($response->json(), $response->meta());
    }

    /**
     * Update the account.
     *
     * Requires `account:write`. `settings` is deep-merged: arrays are replaced whole, language maps merge key
     * by key and `null` removes a language. `settings.limits` is read-only for account keys (`422
     * VALIDATION_FAILED`). Changes are audit-logged.
     *
     * @param  array<string, mixed>|null  $settings  Deep-merged into the account settings; `limits` is refused.
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
    ): Models\Account {
        $response = $this->connection->request(
            'account.update',
            'PATCH',
            '/v1/account',
            body: Params::body([
                'name' => $name ?? Omit::Value,
                'country_iso' => $country_iso ?? Omit::Value,
                'timezone' => $timezone ?? Omit::Value,
                'default_locale' => $default_locale ?? Omit::Value,
                'settings' => Params::object($settings ?? Omit::Value),
            ]),
            options: $requestOptions,
        );

        return Models\Account::fromArray($response->json(), $response->meta());
    }
}
