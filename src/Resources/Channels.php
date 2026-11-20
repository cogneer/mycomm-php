<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Resources;

use Generator;
use MyComm\Enums;
use MyComm\Exceptions\ApiException;
use MyComm\Exceptions\ConnectionException;
use MyComm\Exceptions\InvalidArgumentException;
use MyComm\Http\Connection;
use MyComm\Http\Params;
use MyComm\Inputs;
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
     * Requires `channels:write`; a key restricted to lines never holds it. Changes only the keys sent.
     * `allowed_destination_countries` is `null` (the channel's own country only, the default) or a list of ISO
     * 3166-1 alpha-2 codes that must contain the channel's own country, else `422 VALIDATION_FAILED`; codes
     * are stored upper case. Adding another country is an explicit opt-in on the number and is audited as
     * `channel.destinations.updated`. `skip_business_senders: true` makes the phone stop uploading inbound
     * from short codes and alphanumeric senders (operator-notice senders are always uploaded). `404` when the
     * channel is not found in the user.
     *
     * @param  list<string>|Omit|null  $allowed_destination_countries  `null` is the channel's own country only; a list must contain the channel's own country.
     *
     * @throws InvalidArgumentException when an argument breaks a rule the API documents; nothing is sent (client option `validate`)
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function update(
        string $id,
        ?string $name = null,
        array|Omit|null $allowed_destination_countries = Omit::Value,
        ?bool $skip_business_senders = null,
        ?RequestOptions $requestOptions = null,
    ): Models\Channel {
        $this->connection->guard(
            'channels.update',
            [
                'name' => $name,
                'allowed_destination_countries' => $allowed_destination_countries,
            ],
            [
                'name' => [
                    'minLength' => 1,
                    'maxLength' => 100,
                ],
                'allowed_destination_countries' => [
                    'minItems' => 1,
                ],
            ],
            [],
        );

        $response = $this->connection->request(
            'channels.update',
            'PATCH',
            '/v1/channels/'.rawurlencode($id),
            body: Params::body([
                'name' => $name ?? Omit::Value,
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
     * `user_suspended` only through MyComm: these answer `409 INVALID_STATE` with the channel's `status`, as
     * does a channel that is not paused. Emits `channel.resumed`.
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
     * for use elsewhere and it leaves every line's senders. Its queued outbound messages move to another
     * sender of their line of the same type (`line.failover`, reason `sender_removed`); pinned ones, and those
     * whose line has no such sender left, fail with `CHANNEL_DISCONNECTED`. Lines and history stay readable
     * and sends naming it get `CHANNEL_INACTIVE`. Emits `channel.disconnected`. A `pending_verification`
     * channel that never connected is deleted instead (`204`). An already disconnected channel is `409
     * INVALID_STATE`. The last active or paused channel of a number the user verified as its own is `403
     * SECOND_FACTOR_REQUIRED` (`reason: not_a_user`): only the owner can let that number go, in the portal,
     * with a second factor.
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
     * already active on a channel of the same user is moved to this SIM when verification succeeds. `404` when
     * the phone or SIM is not found in the user or outside the key's scope.
     *
     * @param  string|Omit|null  $address  The SIM's number, national or international form.
     * @param  string|Omit|null  $name  Channel label; default the formatted number.
     *
     * @throws InvalidArgumentException when an argument breaks a rule the API documents; nothing is sent (client option `validate`)
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
        $this->connection->guard(
            'channels.connectSim',
            [
                'address' => $address,
                'name' => $name,
            ],
            [
                'address' => [
                    'maxLength' => 40,
                ],
                'name' => [
                    'maxLength' => 100,
                ],
            ],
            [],
        );

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
     * @throws InvalidArgumentException when an argument breaks a rule the API documents; nothing is sent (client option `validate`)
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function verify(
        string $id,
        string|Omit|null $address = Omit::Value,
        ?RequestOptions $requestOptions = null,
    ): Models\Channel {
        $this->connection->guard(
            'channels.verify',
            [
                'address' => $address,
            ],
            [
                'address' => [
                    'maxLength' => 40,
                ],
            ],
            [],
        );

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
     * Requires `channels:read`. Every channel of the user whatever its status, oldest first, unless a filter
     * narrows the list. `health_band` filters on the stored SIM safety band, so a number that was never
     * evaluated matches no band. A key restricted to lines sees only the channels of those lines.
     *
     * @param  ?string  $status  One of: `pending_verification`, `active`, `paused`, `disconnected`.
     * @param  ?string  $type  One of: `android_sim`, `whatsapp_web`, `telegram_bot`.
     * @param  ?string  $health_band  One of: `good`, `fair`, `poor`, `critical`.
     * @param  ?int  $limit  Page size, 1 to 200.
     * @param  ?string  $cursor  Opaque cursor from a previous page's `next_cursor`; valid for 24 hours.
     * @return Page<Models\Channel>
     *
     * @throws InvalidArgumentException when an argument breaks a rule the API documents; nothing is sent (client option `validate`)
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
        $this->connection->guard(
            'channels.list',
            [
                'device_id' => $device_id,
                'limit' => $limit,
            ],
            [
                'device_id' => [
                    'uuid' => true,
                ],
                'limit' => [
                    'min' => 1,
                    'max' => 200,
                ],
            ],
            [],
        );

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
     * @param  ?string  $type  One of: `android_sim`, `whatsapp_web`, `telegram_bot`.
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
     * Connect a WhatsApp number.
     *
     * Requires `channels:write`. Creates a number of a type that is linked to the phone that holds it (today
     * `whatsapp_web`, a WhatsApp linked device; a preview type that support turns on per user) and starts
     * linking it. The answer is the channel in `pending_verification` with an empty `address`; poll `GET
     * /v1/channels/{id}/connect` for the pairing code or QR and for the outcome. Once the phone links, the
     * channel becomes `active` with the linked number as its `address`, and `channel.connected` is sent.
     *
     * `connect.method` `pairing_code` (offered first: it works with one phone) needs `connect.phone`, the
     * WhatsApp number in E.164 (`+923001234567`); WhatsApp shows a code to type on that phone. `qr_scan` shows
     * a QR to scan with it. A pending connect of the user for the same phone (for a QR, one with no phone) is
     * returned instead of a second channel. A SIM is connected with `POST
     * /v1/devices/{id}/sims/{sim_id}/connect`; any other type, or a type not turned on for the user, is `422
     * CHANNEL_TYPE_NOT_ALLOWED` `{type}`. A missing `type` (or one that is not a string) is `422
     * CHANNEL_TYPE_NOT_ALLOWED` too, with no `details.type`, never `VALIDATION_FAILED`.
     *
     * Refusals: `422 CHANNEL_LIMIT_REACHED` `{limit}` at the user's number limit; `409 CHANNEL_NUMBER_IN_USE`
     * when the number is live on another of the user's channels (`details.channel_id` when that channel was
     * logged out: link it again there with `POST /v1/channels/{id}/connect`); `429 CHANNEL_LINK_BLOCKED`
     * `{kind, retry_after_seconds}` after three failed links in 24 hours (`attempts`) or five link starts in
     * 15 minutes (`rate`), with `Retry-After`; `409 CHANNEL_LINK_BUSY` while another connect step of the user
     * is running (retry in a few seconds); `503 CHANNEL_NO_CAPACITY` when no server has room and `503
     * CHANNEL_SERVER_UNAVAILABLE` (with `Retry-After`) when the server cannot be reached. A refused or failed
     * create leaves no channel behind. A number another user holds is only refused once the phone has linked
     * it: the poll then answers `failed` with `refusal.code` `number_in_use`.
     *
     * @param  string  $type  The channel type; `whatsapp_web`. Echoed in `details.type` of `CHANNEL_TYPE_NOT_ALLOWED`. Missing, it answers `CHANNEL_TYPE_NOT_ALLOWED` without `details.type`.
     * @param  Inputs\ChannelConnectInput|array<string, mixed>  $connect
     * @param  string|Omit|null  $name  Channel label; default empty (the portal shows the number).
     *
     * @throws InvalidArgumentException when an argument breaks a rule the API documents; nothing is sent (client option `validate`)
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function create(
        string $type,
        Inputs\ChannelConnectInput|array $connect,
        string|Omit|null $name = Omit::Value,
        ?RequestOptions $requestOptions = null,
    ): Models\Channel {
        $this->connection->guard(
            'channels.create',
            [
                'name' => $name,
            ],
            [
                'name' => [
                    'maxLength' => 100,
                ],
            ],
            [],
        );

        $response = $this->connection->request(
            'channels.create',
            'POST',
            '/v1/channels',
            body: Params::body([
                'type' => $type,
                'name' => $name,
                'connect' => Params::object($connect),
            ]),
            options: $requestOptions,
        );

        return Models\Channel::fromArray($response->json(), $response->meta());
    }

    /**
     * Get the connect state of a channel.
     *
     * Requires `channels:read`. The poll of a number being linked: the pairing code or the QR while the phone
     * must act, why linking stopped (`refusal`), or, once linked, the number's own state. Poll every few
     * seconds until `state` is no longer `connecting` or `awaiting_link`; the server is asked at most once
     * every 3 seconds per channel and polls in between get the last answer. It reads no query parameters and
     * never starts or changes a link. The answer is sent with `Cache-Control: no-store`: the code and QR are
     * for the person linking only. A SIM channel has no connect state (`404 NOT_FOUND`), nor has a channel
     * outside the key's scope.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function connectState(
        string $id,
        ?RequestOptions $requestOptions = null,
    ): Models\ChannelConnectState {
        $response = $this->connection->request('channels.connectState', 'GET', '/v1/channels/'.rawurlencode($id).'/connect', options: $requestOptions);

        return Models\ChannelConnectState::fromArray($response->json(), $response->meta());
    }

    /**
     * Try linking a number again.
     *
     * Requires `channels:write`. Starts linking again, on the same channel: - `retry`: a
     * `pending_verification` channel, after a failed or expired attempt or when the code or QR ran out; with
     * `method` (default: the one it had) and optionally a corrected `phone` for a pairing code (default: the
     * one entered before). - `switch_method`: the same with the other `method` (`method` is required, and the
     * one the connect already uses is `422 VALIDATION_FAILED` on `method`: send `retry` for that), so a QR
     * replaces a code or the other way round without entering the phone again. - `relink`: a number whose
     * session was logged out (its connect state is `needs_relink`, the channel paused `auto:session_lost`), on
     * the number it had (`phone` is refused); by `pairing_code` unless `method` says `qr_scan`. History, lines
     * and conversations stay; once the phone links the same number the channel resumes.
     *
     * The answer is the new attempt's connect state; keep polling `GET /v1/channels/{id}/connect`. The pairing
     * code and QR are never kept for an `Idempotency-Key` replay: a replay answers the same state with
     * `pairing_code` and `qr_png_base64` `null` (and starts nothing); poll for them. Any other state is `409
     * INVALID_STATE` `{status, allowed_from}`. A pairing code with no phone ever entered is `422
     * ADDRESS_REQUIRED`. A type switched off, or no longer granted to the user, is `422
     * CHANNEL_TYPE_NOT_ALLOWED` `{type}`. A key restricted to lines cannot call it (`403 ABILITY_MISSING`).
     * The refusals of `POST /v1/channels` apply (`CHANNEL_LINK_BLOCKED`, `CHANNEL_LINK_BUSY`,
     * `CHANNEL_NUMBER_IN_USE`, `CHANNEL_NO_CAPACITY`, `CHANNEL_SERVER_UNAVAILABLE`); a refused step changes
     * nothing. A SIM channel is `404 NOT_FOUND`; retry its verification with `POST /v1/channels/{id}/verify`.
     *
     * @param  string|Omit|null  $phone  The WhatsApp number in E.164 for a pairing code; replaces the one entered before.
     *
     * @throws InvalidArgumentException when an argument breaks a rule the API documents; nothing is sent (client option `validate`)
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function continueConnect(
        string $id,
        Enums\ChannelConnectAction|string $action,
        Enums\ChannelLinkMethod|string|Omit|null $method = Omit::Value,
        string|Omit|null $phone = Omit::Value,
        ?RequestOptions $requestOptions = null,
    ): Models\ChannelConnectState {
        $this->connection->guard(
            'channels.continueConnect',
            [
                'action' => $action,
                'method' => $method,
                'phone' => $phone,
            ],
            [
                'action' => [
                    'enum' => ['retry', 'switch_method', 'relink'],
                ],
                'method' => [
                    'enum' => ['pairing_code', 'qr_scan'],
                ],
                'phone' => [
                    'maxLength' => 20,
                ],
            ],
            [],
        );

        $response = $this->connection->request(
            'channels.continueConnect',
            'POST',
            '/v1/channels/'.rawurlencode($id).'/connect',
            body: Params::body([
                'action' => $action,
                'method' => $method,
                'phone' => $phone,
            ]),
            options: $requestOptions,
        );

        return Models\ChannelConnectState::fromArray($response->json(), $response->meta());
    }

    /**
     * Update the rate policy of a channel.
     *
     * Requires `channels:write`; a key restricted to lines never holds it. Sent keys are merged into the
     * current policy (nested objects key by key; a key sent as `null` clears it where the field allows `null`)
     * and `rate_policy_source` becomes `custom`. `{"reset_to_suggested": true}` restores the suggestion
     * (`rate_policy_source: suggested`) and clears the acknowledgement; it cannot be combined with policy
     * fields. A body that changes nothing is `422 VALIDATION_FAILED`.
     *
     * Checks run in this order and the first failure answers:
     *
     * 1. Shape and field bounds, `422 VALIDATION_FAILED`: every cap at least 1 and `per_min <= per_15m <=
     * per_hour <= per_day`, `interval_ms.min >= 60000 / ceiling.per_min`, `interval_ms.max` between `min` and
     * 600000, `bulk_hours` within 07:00 to 22:00 sender-local. 2. Every cap at or below the channel's
     * `rate_policy_ceiling`, else `422 ABOVE_PLATFORM_CEILING` with `{field, value, ceiling, reason}`
     * (`platform`, `country`, `override_limit`, `new_user` or `restricted_country`). During the first 14 days
     * of the user the suggestion is also the shortest wait allowed: `interval_ms.min` below the suggested
     * shortest wait, or `interval_ms.max` below the suggested longest wait, answers `422
     * ABOVE_PLATFORM_CEILING` with `reason: new_user`; for these two fields `value` and `ceiling` are waits in
     * milliseconds and `ceiling` is the shortest wait allowed. A cap above its ceiling is answered first.
     * Acknowledging the risk never lifts a ceiling. After day 14 only step 1 limits a wait.
     * `rate_policy_ceiling` itself holds caps only. 3. The risk of the result: saving `amber` or `red` needs
     * `acknowledge_risk: true`, again whenever the risk rises above the acknowledged level, else `422
     * RISK_ACKNOWLEDGEMENT_REQUIRED` with `{risk, exceeds}`.
     *
     * Saving sets `acknowledged_risk_at` and is audited as `channel.rate_policy.updated`. While the SIM safety
     * band is `poor` or `critical` a custom policy is also capped at the suggestion times the band factor; the
     * stored policy is unchanged and applies again when the band recovers. `404` when the channel is not found
     * in the user.
     *
     * @param  array{per_min?: int, per_15m?: int, per_hour?: int, per_day?: int, new_recipients_per_day?: int|null}|null  $caps
     * @param  array{min?: int, max?: int}|null  $interval_ms
     * @param  array{enabled?: bool}|null  $warmup
     * @param  array{start: string, end: string, timezone?: string|null}|Omit|null  $active_hours  The owner's sending window; `null` means any time. `timezone` `null` is the user's time zone.
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
     * Requires `channels:read`. Every channel type with what this user can connect and what each type can do,
     * so a partner UI adapts without hard-coding SMS. `available` is `true` when the type is enabled on the
     * platform and allowed by the user's `settings.channels.allowed_types`; a type that has not shipped yet
     * carries `available_from`. In v1 only `android_sim` is available. The list is about the user, not a line,
     * so a key restricted to lines gets the full answer. Not paginated.
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
