<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Resources;

use Generator;
use MyComm\Exceptions\ApiException;
use MyComm\Exceptions\ConnectionException;
use MyComm\Http\Connection;
use MyComm\Http\Params;
use MyComm\Input;
use MyComm\Models;
use MyComm\Omit;
use MyComm\Page;
use MyComm\RequestOptions;

final class Channels
{
    public function __construct(private readonly Connection $connection) {}

    /**
     * Get a channel.
     *
     * Requires `channels:read`. The full state of a number: status, the resolved `capabilities`, the rate
     * policy with its suggestion and ceiling, warm-up, the SIM safety score, the queue and rolling usage
     * counts and the latest verification. `404` when the channel is outside the key's scope.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function get(
        string $id,
        ?RequestOptions $requestOptions = null,
    ): Models\Channel {
        $response = $this->connection->request('channels.get', 'GET', '/v1/channels/'.rawurlencode($id), options: $requestOptions);

        return Models\Channel::fromArray($response->json(), $response->meta());
    }

    /**
     * Update the settings of a channel.
     *
     * Requires `channels:write`; a key restricted to inboxes never holds it. Changes only the keys sent.
     * `allowed_destination_countries` is `null` (the channel's own country only, the default) or a list of ISO
     * 3166-1 alpha-2 codes that must contain the channel's own country, else `422 VALIDATION_FAILED`; codes
     * are stored upper case. Adding another country is an explicit opt-in on the number and is audited as
     * `channel.destinations.updated`. `skip_business_senders: true` makes the phone stop uploading inbound
     * from short codes and alphanumeric senders (operator-notice senders are always uploaded). `404` when the
     * channel is not found in the account.
     *
     * @param  list<string>|Omit|null  $allowed_destination_countries  `null` is the channel's own country only; a list must contain the channel's own country.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function update(
        string $id,
        ?string $name = null,
        ?int $reply_window_days = null,
        array|Omit|null $allowed_destination_countries = Omit::Value,
        ?bool $skip_business_senders = null,
        ?RequestOptions $requestOptions = null,
    ): Models\Channel {
        $response = $this->connection->request(
            'channels.update',
            'PATCH',
            '/v1/channels/'.rawurlencode($id),
            body: Params::body([
                'name' => $name ?? Omit::Value,
                'reply_window_days' => $reply_window_days ?? Omit::Value,
                'allowed_destination_countries' => $allowed_destination_countries,
                'skip_business_senders' => $skip_business_senders ?? Omit::Value,
            ]),
            options: $requestOptions,
        );

        return Models\Channel::fromArray($response->json(), $response->meta());
    }

    /**
     * Pause a channel.
     *
     * Requires `channels:write`. `active` → `paused` with `pause_reason: owner`. Messages are still accepted
     * and wait until the channel is resumed. Emits `channel.paused`. A channel in any other state is `409
     * INVALID_STATE` with `allowed_from: [active]`.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function pause(
        string $id,
        ?RequestOptions $requestOptions = null,
    ): Models\Channel {
        $response = $this->connection->request('channels.pause', 'POST', '/v1/channels/'.rawurlencode($id).'/pause', options: $requestOptions);

        return Models\Channel::fromArray($response->json(), $response->meta());
    }

    /**
     * Resume a paused channel.
     *
     * Requires `channels:write`. `paused` → `active` for `pause_reason` `owner`, `auto:failure_streak` and
     * `auto:quota` (after the SIM was recharged); `auto:health_critical` also once the SIM safety band is back
     * to `fair` or better. `auto:sim_missing` and `device_revoked` resume through the SIM returning or
     * re-verification, `auto:quarantine` through a successful test message or MyComm support, `admin` and
     * `account_suspended` only through MyComm: these answer `409 INVALID_STATE` with the channel's `status`,
     * as does a channel that is not paused. Emits `channel.resumed`.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function resume(
        string $id,
        ?RequestOptions $requestOptions = null,
    ): Models\Channel {
        $response = $this->connection->request('channels.resume', 'POST', '/v1/channels/'.rawurlencode($id).'/resume', options: $requestOptions);

        return Models\Channel::fromArray($response->json(), $response->meta());
    }

    /**
     * Disconnect a channel.
     *
     * Requires `channels:write`. → `disconnected` with `disconnect_reason: owner`; the number is released
     * for use elsewhere, queued outbound messages fail with `CHANNEL_DISCONNECTED`, inboxes and history stay
     * readable and sends get `CHANNEL_INACTIVE`. Emits `channel.disconnected`. A `pending_verification`
     * channel that never connected is deleted instead (`204`). An already disconnected channel is `409
     * INVALID_STATE`.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function disconnect(
        string $id,
        ?RequestOptions $requestOptions = null,
    ): ?Models\Channel {
        $response = $this->connection->request('channels.disconnect', 'POST', '/v1/channels/'.rawurlencode($id).'/disconnect', options: $requestOptions);

        if ($response->body === '') {
            return null;
        }

        return Models\Channel::fromArray($response->json(), $response->meta());
    }

    /**
     * Connect a SIM.
     *
     * Requires `channels:write`. Creates a channel in `pending_verification` for the SIM and asks the person
     * holding the phone to confirm the connection; while the prompt is open `verification.step` is
     * `awaiting_confirmation` and `verification.confirmation.status` is `pending`. After the tap the phone
     * texts a 6-digit code to the number and catches it. The outcome arrives as `channel.connected` or
     * `channel.verification_failed` and on `GET /v1/channels/{id}`. `address` is optional when the SIM reports
     * its number or the platform's inbound probe is configured; otherwise `422 ADDRESS_REQUIRED`. A number
     * already active on a channel of the same account is moved to this SIM when verification succeeds. `404`
     * when the phone or SIM is not found in the account or outside the key's scope.
     *
     * @param  string|Omit|null  $address  The SIM's number, national or international form.
     * @param  string|Omit|null  $name  Channel label; default the formatted number.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function connectSim(
        string $id,
        string $sim_id,
        string|Omit|null $address = Omit::Value,
        string|Omit|null $name = Omit::Value,
        ?RequestOptions $requestOptions = null,
    ): Models\Channel {
        $response = $this->connection->request(
            'channels.connectSim',
            'POST',
            '/v1/devices/'.rawurlencode($id).'/sims/'.rawurlencode($sim_id).'/connect',
            body: Params::body([
                'address' => $address,
                'name' => $name,
            ]),
            options: $requestOptions,
        );

        return Models\Channel::fromArray($response->json(), $response->meta());
    }

    /**
     * Retry the verification of a channel.
     *
     * Requires `channels:write`. Retries the verification of a `pending_verification` channel: a new attempt
     * after a failed one, or a new verification after a `failed` or `expired` one, optionally with a corrected
     * `address`. An attempt that is still running is left as it is. It also re-verifies a channel paused with
     * `auto:sim_missing` on the SIM now in its phone; the channel keeps its number and stays paused until the
     * verification succeeds. A channel in any other state is `409 INVALID_STATE`. The checks are those of
     * connect. `422 VERIFICATION_LIMIT_REACHED` says in `retry_after_seconds` when to try again.
     *
     * @param  string|Omit|null  $address  A corrected number; it replaces the one the channel was started with.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function verify(
        string $id,
        string|Omit|null $address = Omit::Value,
        ?RequestOptions $requestOptions = null,
    ): Models\Channel {
        $response = $this->connection->request(
            'channels.verify',
            'POST',
            '/v1/channels/'.rawurlencode($id).'/verify',
            body: Params::body([
                'address' => $address,
            ]),
            options: $requestOptions,
        );

        return Models\Channel::fromArray($response->json(), $response->meta());
    }

    /**
     * List channels.
     *
     * Requires `channels:read`. Every channel of the account whatever its status, oldest first, unless a
     * filter narrows the list. `health_band` filters on the stored SIM safety band, so a number that was never
     * evaluated matches no band. A key restricted to inboxes sees only the channels of those inboxes (03
     * sections 2.3, 3.11.4).
     *
     * @param  ?string  $status  One of: `pending_verification`, `active`, `paused`, `disconnected`.
     * @param  ?string  $type  One of: `android_sim`, `whatsapp_evolution`, `telegram_bot`.
     * @param  ?string  $health_band  One of: `good`, `fair`, `poor`, `critical`.
     * @param  ?int  $limit  Page size, 1 to 200.
     * @param  ?string  $cursor  Opaque cursor from a previous page's `next_cursor`; valid for 24 hours.
     * @return Page<Models\Channel>
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function list(
        ?string $status = null,
        ?string $type = null,
        ?string $device_id = null,
        ?string $health_band = null,
        ?int $limit = null,
        ?string $cursor = null,
        ?RequestOptions $requestOptions = null,
    ): Page {
        $response = $this->connection->request(
            'channels.list',
            'GET',
            '/v1/channels',
            query: Params::query([
                'status' => $status,
                'type' => $type,
                'device_id' => $device_id,
                'health_band' => $health_band,
                'limit' => $limit,
                'cursor' => $cursor,
            ]),
            options: $requestOptions,
        );

        return Page::fromResponse($response, static fn (array $item): Models\Channel => Models\Channel::fromArray($item), fn (string $next): Page => $this->list(status: $status, type: $type, device_id: $device_id, health_band: $health_band, limit: $limit, cursor: $next, requestOptions: $requestOptions));
    }

    /**
     * Every item of `list()`, fetching the following pages as the loop reaches them.
     *
     * `limit` is the page size, not a cap on the items.
     *
     * @param  ?string  $status  One of: `pending_verification`, `active`, `paused`, `disconnected`.
     * @param  ?string  $type  One of: `android_sim`, `whatsapp_evolution`, `telegram_bot`.
     * @param  ?string  $health_band  One of: `good`, `fair`, `poor`, `critical`.
     * @param  ?int  $limit  Page size, 1 to 200.
     * @return Generator<int, Models\Channel>
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function all(
        ?string $status = null,
        ?string $type = null,
        ?string $device_id = null,
        ?string $health_band = null,
        ?int $limit = null,
        ?RequestOptions $requestOptions = null,
    ): Generator {
        return $this->list(status: $status, type: $type, device_id: $device_id, health_band: $health_band, limit: $limit, requestOptions: $requestOptions)->autoPaging();
    }

    /**
     * Connect a push channel.
     *
     * Requires `channels:write`. Connecting a push channel (WhatsApp, Telegram) is part of v2; v1 answers `422
     * CHANNEL_TYPE_NOT_ALLOWED` to every request, whatever its body. A SIM is connected with `POST
     * /v1/devices/{id}/sims/{sim_id}/connect`.
     *
     * @param  ?string  $type  The channel type asked for; echoed in `details.type` of the error.
     * @param  array<string, mixed>|null  $connect  The type's connect input (v2).
     * @return array<string, mixed>
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function create(
        ?string $type = null,
        ?string $name = null,
        ?array $connect = null,
        ?RequestOptions $requestOptions = null,
    ): array {
        return $this->connection->request(
            'channels.create',
            'POST',
            '/v1/channels',
            body: Params::body([
                'type' => $type ?? Omit::Value,
                'name' => $name ?? Omit::Value,
                'connect' => Params::object($connect ?? Omit::Value),
            ]),
            options: $requestOptions,
        )->json();
    }

    /**
     * Get the connect state of a push channel.
     *
     * Requires `channels:read`. In v2 this returns the current QR code or pairing code of a connecting
     * WhatsApp channel. A SIM channel has none, so v1 answers `404 NOT_FOUND` for every channel.
     *
     * @return array<string, mixed>
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function connectState(
        string $id,
        ?RequestOptions $requestOptions = null,
    ): array {
        return $this->connection->request('channels.connectState', 'GET', '/v1/channels/'.rawurlencode($id).'/connect', options: $requestOptions)->json();
    }

    /**
     * Update the rate policy of a channel.
     *
     * Requires `channels:write`; a key restricted to inboxes never holds it. Sent keys are merged into the
     * current policy (nested objects key by key; a key sent as `null` clears it where the field allows `null`)
     * and `rate_policy_source` becomes `custom`. `{"reset_to_suggested": true}` restores the suggestion
     * (`rate_policy_source: suggested`) and clears the acknowledgement; it cannot be combined with policy
     * fields. A body that changes nothing is `422 VALIDATION_FAILED`.
     *
     * Checks run in this order and the first failure answers:
     *
     * 1. Shape and field bounds of 02 section 2.1, `422 VALIDATION_FAILED`: every cap at least 1 and `per_min
     * <= per_15m <= per_hour <= per_day`, `interval_ms.min >= 60000 / ceiling.per_min`, `interval_ms.max`
     * between `min` and 600000, `bulk_hours` within 07:00 to 22:00 sender-local. 2. Every cap at or below the
     * channel's `rate_policy_ceiling`, else `422 ABOVE_PLATFORM_CEILING` with `{field, value, ceiling,
     * reason}` (`platform`, `country`, `override_limit`, `new_account` or `restricted_country`). During the
     * first 14 days of the account the suggestion is also the shortest wait allowed: `interval_ms.min` below
     * the suggested shortest wait, or `interval_ms.max` below the suggested longest wait, answers `422
     * ABOVE_PLATFORM_CEILING` with `reason: new_account`; for these two fields `value` and `ceiling` are waits
     * in milliseconds and `ceiling` is the shortest wait allowed. A cap above its ceiling is answered first.
     * Acknowledging the risk never lifts a ceiling. After day 14 only step 1 limits a wait.
     * `rate_policy_ceiling` itself holds caps only. 3. The risk of the result: saving `amber` or `red` needs
     * `acknowledge_risk: true`, again whenever the risk rises above the acknowledged level, else `422
     * RISK_ACKNOWLEDGEMENT_REQUIRED` with `{risk, exceeds}`.
     *
     * Saving sets `acknowledged_risk_at` and is audited as `channel.rate_policy.updated`. While the SIM safety
     * band is `poor` or `critical` a custom policy is also capped at the suggestion times the band factor; the
     * stored policy is unchanged and applies again when the band recovers. `404` when the channel is not found
     * in the account.
     *
     * @param  array{per_min?: int, per_15m?: int, per_hour?: int, per_day?: int, new_recipients_per_day?: int|null}|null  $caps
     * @param  array{min?: int, max?: int}|null  $interval_ms
     * @param  array{enabled?: bool}|null  $warmup
     * @param  array{start: string, end: string, timezone?: string|null}|Omit|null  $active_hours  The owner's sending window; `null` means any time. `timezone` `null` is the account time zone.
     * @param  array{start: string, end: string, tz?: string|null}|Omit|null  $bulk_hours  The window of the `bulk` lane within 07:00 to 22:00 sender-local. `tz` `null` is the channel country's time zone.
     * @param  ?bool  $acknowledge_risk  Required to save an `amber` or `red` policy, again whenever the risk rises above the acknowledged level.
     * @param  ?bool  $reset_to_suggested  `true` restores the suggestion and clears the acknowledgement; not allowed together with policy fields.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function updateRatePolicy(
        string $id,
        ?array $caps = null,
        ?array $interval_ms = null,
        ?array $warmup = null,
        array|Omit|null $active_hours = Omit::Value,
        array|Omit|null $bulk_hours = Omit::Value,
        ?bool $acknowledge_risk = null,
        ?bool $reset_to_suggested = null,
        ?RequestOptions $requestOptions = null,
    ): Models\Channel {
        $response = $this->connection->request(
            'channels.updateRatePolicy',
            'PATCH',
            '/v1/channels/'.rawurlencode($id).'/rate-policy',
            body: Params::body([
                'caps' => Params::object($caps ?? Omit::Value),
                'interval_ms' => Params::object($interval_ms ?? Omit::Value),
                'warmup' => Params::object($warmup ?? Omit::Value),
                'active_hours' => Params::object($active_hours),
                'bulk_hours' => Params::object($bulk_hours),
                'acknowledge_risk' => $acknowledge_risk ?? Omit::Value,
                'reset_to_suggested' => $reset_to_suggested ?? Omit::Value,
            ]),
            options: $requestOptions,
        );

        return Models\Channel::fromArray($response->json(), $response->meta());
    }

    /**
     * List channel types.
     *
     * Requires `channels:read`. Every channel type with what this account can connect and what each type can
     * do, so a partner UI adapts without hard-coding SMS. `available` is `true` when the type is enabled on
     * the platform and allowed by the account's `settings.channels.allowed_types`; a type that has not shipped
     * yet carries `available_from`. In v1 only `android_sim` is available. The list is about the account, not
     * an inbox, so a key restricted to inboxes gets the full answer. Not paginated.
     *
     * @return Page<Models\ChannelTypeInfo>
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function types(
        ?RequestOptions $requestOptions = null,
    ): Page {
        $response = $this->connection->request('channels.types', 'GET', '/v1/channels/types', options: $requestOptions);

        return Page::fromResponse($response, static fn (array $item): Models\ChannelTypeInfo => Models\ChannelTypeInfo::fromArray($item), null);
    }
}
