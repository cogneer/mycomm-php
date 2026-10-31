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

final class Lines
{
    public readonly Lines\Links $links;

    public function __construct(private readonly Connection $connection)
    {
        $this->links = new Lines\Links($connection);
    }

    /**
     * List lines.
     *
     * Requires `channels:read`. Archived lines are hidden unless `archived=true`. A key restricted to lines
     * sees only those.
     *
     * @param  ?bool  $archived  Default `false`.
     * @param  ?string  $channel_id  Only lines that have this channel in their sender list.
     * @param  ?string  $channel_type  Only lines with a sender of this type. One of: `android_sim`, `whatsapp_evolution`, `telegram_bot`.
     * @param  ?int  $limit  Page size, 1 to 200.
     * @param  ?string  $cursor  Opaque cursor from a previous page's `next_cursor`; valid for 24 hours.
     * @return Page<Models\Line>
     *
     * @throws InvalidArgumentException when an argument breaks a rule the API documents; nothing is sent (client option `validate`)
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function list(
        Enums\LineKind|string|null $kind = null,
        ?bool $archived = null,
        ?string $channel_id = null,
        ?string $channel_type = null,
        ?int $limit = null,
        ?string $cursor = null,
        ?RequestOptions $requestOptions = null,
    ): Page {
        $this->connection->guard(
            'lines.list',
            [
                'channel_id' => $channel_id,
                'limit' => $limit,
            ],
            [
                'channel_id' => [
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
            'lines.list',
            'GET',
            '/v1/lines',
            query: Params::query([
                'kind' => $kind,
                'archived' => $archived,
                'channel_id' => $channel_id,
                'channel_type' => $channel_type,
                'limit' => $limit,
                'cursor' => $cursor,
            ]),
            options: $requestOptions,
        );

        return Page::fromResponse($response, static fn (array $item): Models\Line => Models\Line::fromArray($item), fn (string $next): Page => $this->list(kind: $kind, archived: $archived, channel_id: $channel_id, channel_type: $channel_type, limit: $limit, cursor: $next, requestOptions: $requestOptions));
    }

    /**
     * Every item of `list()`, fetching the following pages as the loop reaches them.
     *
     * `limit` is the page size, not a cap on the items.
     *
     * @param  ?bool  $archived  Default `false`.
     * @param  ?string  $channel_id  Only lines that have this channel in their sender list.
     * @param  ?string  $channel_type  Only lines with a sender of this type. One of: `android_sim`, `whatsapp_evolution`, `telegram_bot`.
     * @param  ?int  $limit  Page size, 1 to 200.
     * @return Generator<int, Models\Line>
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function all(
        Enums\LineKind|string|null $kind = null,
        ?bool $archived = null,
        ?string $channel_id = null,
        ?string $channel_type = null,
        ?int $limit = null,
        ?RequestOptions $requestOptions = null,
    ): Generator {
        return $this->list(kind: $kind, archived: $archived, channel_id: $channel_id, channel_type: $channel_type, limit: $limit, requestOptions: $requestOptions)->autoPaging();
    }

    /**
     * Create an app line.
     *
     * Requires `channels:write`, which a key restricted to lines never holds. Adds an `app` line that sends
     * through `senders` in order: the first entry is the primary unless one is marked `primary`, which is then
     * placed first (the others keep their order), so the primary always sends first; the others are backups.
     * Every channel must be one of the account's, connected and verified, and appear once (`422
     * VALIDATION_FAILED` on `senders`; a channel still pending verification or disconnected answers `422
     * CHANNEL_INACTIVE`). `409 NAME_TAKEN` `{field: "name"}` when another line of the account has the name
     * (case-insensitive, the Direct line's included). A line without senders is allowed; it can send once it
     * has one.
     *
     * @param  list<Inputs\LineSenderInput|array<string, mixed>>|null  $senders
     *
     * @throws InvalidArgumentException when an argument breaks a rule the API documents; nothing is sent (client option `validate`)
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function create(
        string $name,
        ?array $senders = null,
        ?RequestOptions $requestOptions = null,
    ): Models\Line {
        $this->connection->guard(
            'lines.create',
            [
                'name' => $name,
                'senders' => $senders,
            ],
            [
                'name' => [
                    'minLength' => 1,
                    'maxLength' => 120,
                ],
                'senders' => [
                    'maxItems' => 50,
                ],
            ],
            [],
        );

        $response = $this->connection->request(
            'lines.create',
            'POST',
            '/v1/lines',
            body: Params::body([
                'name' => $name,
                'senders' => $senders ?? Omit::Value,
            ]),
            options: $requestOptions,
        );

        return Models\Line::fromArray($response->json(), $response->meta());
    }

    /**
     * Get a line.
     *
     * Requires `channels:read`. `404` when the line is outside the key's scope.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function get(
        string $id,
        ?RequestOptions $requestOptions = null,
    ): Models\Line {
        $response = $this->connection->request('lines.get', 'GET', '/v1/lines/'.rawurlencode($id), options: $requestOptions);

        return Models\Line::fromArray($response->json(), $response->meta());
    }

    /**
     * Update a line.
     *
     * Requires `channels:write`. Every field is optional. `reply_window_days` (1 to 365) is how long a reply
     * on a channel this line wrote from comes back to this line. `settings` keys are merged into the stored
     * settings (a key set to `null` is removed). `409 NAME_TAKEN` `{field: "name"}` when another line of the
     * account has the name (case-insensitive). The Direct line keeps its name: a different `name` answers `422
     * LINE_IS_DIRECT` `{line_id}`, while its other fields change as usual. `send_mode`, `sticky_sender` and
     * `failover_seconds` set how the line picks its sender; `failover_seconds` is merged lane by lane into the
     * stored hold times (`null` restores the defaults). `prefer` sets the channel type the line sends with
     * first and `cross_type_fallback` (merged into the stored value) resends an undelivered message through
     * the next type; a fallback may double the cost of a message. `on_unconfirmed` decides what happens to a
     * message an unpaired phone never confirmed, and `alarm_thresholds` (merged into the stored values, `null`
     * restores the defaults) when the line's `line.alarm` fires. The sender list changes with `PUT
     * /lines/{id}/senders`.
     *
     * @param  array{enabled?: bool, after_seconds?: int}|null  $cross_type_fallback  The fallback fields to change; fields left out keep theirs.
     * @param  array{high?: int, normal?: int, bulk?: int}|Omit|null  $failover_seconds  Hold times to change, by lane (30 to 7200 seconds each); lanes left out keep theirs. `null` restores the defaults.
     * @param  array{budget_percent?: int, self_loop_per_hour?: int}|Omit|null  $alarm_thresholds  Alarm thresholds to change; fields left out keep theirs. `null` restores the defaults.
     * @param  array<string, mixed>|null  $settings  Merged key by key into the line settings; `null` removes a key. The hold times change through `failover_seconds` and the alarm thresholds through `alarm_thresholds`, not here.
     *
     * @throws InvalidArgumentException when an argument breaks a rule the API documents; nothing is sent (client option `validate`)
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function update(
        string $id,
        ?string $name = null,
        ?int $reply_window_days = null,
        ?bool $show_in_portal = null,
        Enums\LineSendMode|string|null $send_mode = null,
        ?bool $sticky_sender = null,
        Enums\LinePrefer|string|null $prefer = null,
        ?array $cross_type_fallback = null,
        array|Omit|null $failover_seconds = Omit::Value,
        Enums\LineOnUnconfirmed|string|null $on_unconfirmed = null,
        array|Omit|null $alarm_thresholds = Omit::Value,
        ?array $settings = null,
        ?RequestOptions $requestOptions = null,
    ): Models\Line {
        $this->connection->guard(
            'lines.update',
            [
                'name' => $name,
                'reply_window_days' => $reply_window_days,
                'send_mode' => $send_mode,
                'prefer' => $prefer,
                'on_unconfirmed' => $on_unconfirmed,
            ],
            [
                'name' => [
                    'minLength' => 1,
                    'maxLength' => 120,
                ],
                'reply_window_days' => [
                    'min' => 1,
                    'max' => 365,
                ],
                'send_mode' => [
                    'enum' => ['ordered', 'pool'],
                ],
                'prefer' => [
                    'enum' => ['auto', 'sms', 'whatsapp'],
                ],
                'on_unconfirmed' => [
                    'enum' => ['fail', 'resend'],
                ],
            ],
            [],
        );

        $response = $this->connection->request(
            'lines.update',
            'PATCH',
            '/v1/lines/'.rawurlencode($id),
            body: Params::body([
                'name' => $name ?? Omit::Value,
                'reply_window_days' => $reply_window_days ?? Omit::Value,
                'show_in_portal' => $show_in_portal ?? Omit::Value,
                'send_mode' => $send_mode ?? Omit::Value,
                'sticky_sender' => $sticky_sender ?? Omit::Value,
                'prefer' => $prefer ?? Omit::Value,
                'cross_type_fallback' => Params::object($cross_type_fallback ?? Omit::Value),
                'failover_seconds' => Params::object($failover_seconds),
                'on_unconfirmed' => $on_unconfirmed ?? Omit::Value,
                'alarm_thresholds' => Params::object($alarm_thresholds),
                'settings' => Params::object($settings ?? Omit::Value),
            ]),
            options: $requestOptions,
        );

        return Models\Line::fromArray($response->json(), $response->meta());
    }

    /**
     * Archive a line.
     *
     * Requires `channels:write`. Archives the line; nothing is deleted. The Direct line cannot be archived
     * (`422 LINE_IS_DIRECT`). Sends from an archived line are refused (`LINE_ARCHIVED`), reply routing skips
     * it and its open conversations move to the Direct line. Keys restricted to it keep read access. Archiving
     * an archived line changes nothing. `POST /lines/{id}/unarchive` brings it back.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function archive(
        string $id,
        ?RequestOptions $requestOptions = null,
    ): Models\Line {
        $response = $this->connection->request('lines.archive', 'DELETE', '/v1/lines/'.rawurlencode($id), options: $requestOptions);

        return Models\Line::fromArray($response->json(), $response->meta());
    }

    /**
     * Unarchive a line.
     *
     * Requires `channels:write`. Does nothing when the line is not archived. Takes no body. A partner line
     * that was detached stays archived: `409 INVALID_STATE` `{line_id}`.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function unarchive(
        string $id,
        ?RequestOptions $requestOptions = null,
    ): Models\Line {
        $response = $this->connection->request('lines.unarchive', 'POST', '/v1/lines/'.rawurlencode($id).'/unarchive', options: $requestOptions);

        return Models\Line::fromArray($response->json(), $response->meta());
    }

    /**
     * List a line's senders.
     *
     * Requires `channels:read`. The sender list in order, with each sender's health now. Not paginated.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function listSenders(
        string $id,
        ?RequestOptions $requestOptions = null,
    ): Models\LineSenderList {
        $response = $this->connection->request('lines.listSenders', 'GET', '/v1/lines/'.rawurlencode($id).'/senders', options: $requestOptions);

        return Models\LineSenderList::fromArray($response->json(), $response->meta());
    }

    /**
     * Replace a line's senders.
     *
     * Requires `channels:write`. Replaces the whole list; the order is the position (0 first). At most one
     * entry is `primary`; with none marked the first entry is. A marked primary is placed first and the others
     * keep their order, so role and position always agree. An entry without a role is a `backup`. Every
     * channel must be one of the account's, not disconnected, and appear once (`422 VALIDATION_FAILED` on
     * `senders`). An empty list leaves the line without senders: sends from it are held
     * (`no_sender_available`). Fires `line.sender_changed`. Queued messages on a sender that left the list
     * move to the next healthy sender.
     *
     * @param  list<Inputs\LineSenderInput|array<string, mixed>>  $senders
     *
     * @throws InvalidArgumentException when an argument breaks a rule the API documents; nothing is sent (client option `validate`)
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function setSenders(
        string $id,
        array $senders,
        ?RequestOptions $requestOptions = null,
    ): Models\LineSenderList {
        $this->connection->guard(
            'lines.setSenders',
            [
                'senders' => $senders,
            ],
            [
                'senders' => [
                    'maxItems' => 50,
                ],
            ],
            [],
        );

        $response = $this->connection->request(
            'lines.setSenders',
            'PUT',
            '/v1/lines/'.rawurlencode($id).'/senders',
            body: Params::body([
                'senders' => $senders,
            ]),
            options: $requestOptions,
        );

        return Models\LineSenderList::fromArray($response->json(), $response->meta());
    }
}
