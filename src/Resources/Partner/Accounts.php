<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Resources\Partner;

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

final class Accounts
{
    public function __construct(private readonly Connection $connection)
    {
    }

    /**
     * List managed accounts.
     *
     * A key with `allowed_account_ids` lists only those accounts.
     *
     * @param  ?string  $external_ref  Exact match.
     * @param  ?string  $status  One of: `active`, `suspended`, `pending_deletion`.
     * @param  ?string  $q  Matches the name.
     * @param  ?bool  $needs_attention  `true` for accounts with `counts.channels_needing_attention` above zero.
     * @param  ?string  $sort  One of: `-created_at`, `name`.
     * @param  ?int  $limit  Page size, 1 to 200.
     * @param  ?string  $cursor  Opaque cursor from a previous page's `next_cursor`; valid for 24 hours.
     * @return Page<Models\ManagedAccount>
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function list(
        ?string $external_ref = null,
        ?string $status = null,
        ?string $q = null,
        DateTimeInterface|string|null $created_after = null,
        DateTimeInterface|string|null $created_before = null,
        ?bool $needs_attention = null,
        ?string $sort = null,
        ?int $limit = null,
        ?string $cursor = null,
        ?RequestOptions $requestOptions = null,
    ): Page {
        $response = $this->connection->request(
            'partner.accounts.list',
            'GET',
            '/partner/v1/accounts',
            query: Params::query([
                'external_ref' => $external_ref,
                'status' => $status,
                'q' => $q,
                'created_after' => $created_after,
                'created_before' => $created_before,
                'needs_attention' => $needs_attention,
                'sort' => $sort,
                'limit' => $limit,
                'cursor' => $cursor,
            ]),
            options: $requestOptions,
        );

        return Page::fromResponse($response, static fn (array $item): Models\ManagedAccount => Models\ManagedAccount::fromArray($item), fn (string $next): Page => $this->list(external_ref: $external_ref, status: $status, q: $q, created_after: $created_after, created_before: $created_before, needs_attention: $needs_attention, sort: $sort, limit: $limit, cursor: $next, requestOptions: $requestOptions));
    }

    /**
     * Every item of `list()`, fetching the following pages as the loop reaches them.
     *
     * `limit` is the page size, not a cap on the items.
     *
     * @param  ?string  $external_ref  Exact match.
     * @param  ?string  $status  One of: `active`, `suspended`, `pending_deletion`.
     * @param  ?string  $q  Matches the name.
     * @param  ?bool  $needs_attention  `true` for accounts with `counts.channels_needing_attention` above zero.
     * @param  ?string  $sort  One of: `-created_at`, `name`.
     * @param  ?int  $limit  Page size, 1 to 200.
     * @return Generator<int, Models\ManagedAccount>
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function all(
        ?string $external_ref = null,
        ?string $status = null,
        ?string $q = null,
        DateTimeInterface|string|null $created_after = null,
        DateTimeInterface|string|null $created_before = null,
        ?bool $needs_attention = null,
        ?string $sort = null,
        ?int $limit = null,
        ?RequestOptions $requestOptions = null,
    ): Generator {
        return $this->list(external_ref: $external_ref, status: $status, q: $q, created_after: $created_after, created_before: $created_before, needs_attention: $needs_attention, sort: $sort, limit: $limit, requestOptions: $requestOptions)->autoPaging();
    }

    /**
     * Provision a managed account.
     *
     * Idempotent by `external_ref`. `201` creates a managed account with no users;
     * `channels.auto_connect_sims` defaults to `true`. `200` returns the existing account with this
     * `external_ref` **unchanged**, also when it is `pending_deletion` (the request's other fields are
     * ignored; use `PATCH`). Concurrent duplicates resolve to one account. Keys with `allowed_account_ids` get
     * `403 PARTNER_KEY_RESTRICTED`; read-only keys `403 ABILITY_MISSING`.
     *
     * @param  string  $external_ref  The partner's id for its customer; unique per partner.
     * @param  string  $name  Shown in the Android app after pairing.
     * @param  string  $country_iso  ISO 3166-1 alpha-2.
     * @param  string  $timezone  IANA zone, for example `Asia/Karachi`.
     * @param  ?string  $default_locale  BCP 47, default `en-GB`.
     * @param  array<string, mixed>|null  $settings  Initial settings (subset of 03 section 3.15); `channels.auto_connect_sims` defaults to `true`.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function provision(
        string $external_ref,
        string $name,
        string $country_iso,
        string $timezone,
        ?string $default_locale = null,
        ?array $settings = null,
        ?RequestOptions $requestOptions = null,
    ): Models\ManagedAccount {
        $response = $this->connection->request(
            'partner.accounts.provision',
            'POST',
            '/partner/v1/accounts',
            body: Params::body([
                'external_ref' => $external_ref,
                'name' => $name,
                'country_iso' => $country_iso,
                'timezone' => $timezone,
                'default_locale' => $default_locale ?? Omit::Value,
                'settings' => Params::object($settings ?? Omit::Value),
            ]),
            options: $requestOptions,
        );

        return Models\ManagedAccount::fromArray($response->json(), $response->meta());
    }

    /**
     * Get a managed account.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function get(
        string $id,
        ?RequestOptions $requestOptions = null,
    ): Models\ManagedAccount {
        $response = $this->connection->request('partner.accounts.get', 'GET', '/partner/v1/accounts/'.rawurlencode($id), options: $requestOptions);

        return Models\ManagedAccount::fromArray($response->json(), $response->meta());
    }

    /**
     * Update a managed account.
     *
     * `settings` is deep-merged with the rules of `PATCH /v1/account`, plus `limits.max_daily_segments` and
     * `channels.allowed_types`, which only the partner may set. The new `name` appears in the paired app. A
     * taken `external_ref` is `409 EXTERNAL_REF_TAKEN`.
     *
     * @param  array<string, mixed>|null  $settings  Deep-merged; the partner may also set `limits.max_daily_segments` and `channels.allowed_types`.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function update(
        string $id,
        ?string $external_ref = null,
        ?string $name = null,
        ?string $country_iso = null,
        ?string $timezone = null,
        ?string $default_locale = null,
        ?array $settings = null,
        ?RequestOptions $requestOptions = null,
    ): Models\ManagedAccount {
        $response = $this->connection->request(
            'partner.accounts.update',
            'PATCH',
            '/partner/v1/accounts/'.rawurlencode($id),
            body: Params::body([
                'external_ref' => $external_ref ?? Omit::Value,
                'name' => $name ?? Omit::Value,
                'country_iso' => $country_iso ?? Omit::Value,
                'timezone' => $timezone ?? Omit::Value,
                'default_locale' => $default_locale ?? Omit::Value,
                'settings' => Params::object($settings ?? Omit::Value),
            ]),
            options: $requestOptions,
        );

        return Models\ManagedAccount::fromArray($response->json(), $response->meta());
    }

    /**
     * Schedule deletion of a managed account.
     *
     * Cancels queued messages and unfinished campaigns first, then revokes devices and account keys and
     * disconnects channels. Emits `account.deletion_scheduled`. `reactivate` before `purge_at` (14 days)
     * restores the data.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function delete(
        string $id,
        ?RequestOptions $requestOptions = null,
    ): Models\ManagedAccount {
        $response = $this->connection->request('partner.accounts.delete', 'DELETE', '/partner/v1/accounts/'.rawurlencode($id), options: $requestOptions);

        return Models\ManagedAccount::fromArray($response->json(), $response->meta());
    }

    /**
     * Suspend a managed account.
     *
     * `status: suspended`; `/v1` writes then get `403 ACCOUNT_SUSPENDED`. Emits `account.suspended`.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function suspend(
        string $id,
        string $reason,
        ?RequestOptions $requestOptions = null,
    ): Models\ManagedAccount {
        $response = $this->connection->request(
            'partner.accounts.suspend',
            'POST',
            '/partner/v1/accounts/'.rawurlencode($id).'/suspend',
            body: Params::body([
                'reason' => $reason,
            ]),
            options: $requestOptions,
        );

        return Models\ManagedAccount::fromArray($response->json(), $response->meta());
    }

    /**
     * Reactivate a managed account.
     *
     * From `suspended`, or from `pending_deletion` before `purge_at`. Emits `account.reactivated`. A
     * suspension set by MyComm support or a kill switch is not lifted by a partner (409 SUSPENDED_BY_SUPPORT).
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function reactivate(
        string $id,
        ?RequestOptions $requestOptions = null,
    ): Models\ManagedAccount {
        $response = $this->connection->request('partner.accounts.reactivate', 'POST', '/partner/v1/accounts/'.rawurlencode($id).'/reactivate', options: $requestOptions);

        return Models\ManagedAccount::fromArray($response->json(), $response->meta());
    }
}
