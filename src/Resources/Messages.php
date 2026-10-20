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

final class Messages
{
    public function __construct(private readonly Connection $connection)
    {
    }

    /**
     * Cancel a message.
     *
     * Cancels a `queued` message, held and scheduled ones included. Requires `messages:send`. Answers `200`
     * with the message in `status: cancelled`. A `leased` message is already on the phone, so the call is a
     * best-effort cancel request: `202` with the message still `leased` and `cancel_requested_at` set; the
     * phone's later report decides the outcome. A `sent` or other terminal message answers `409
     * NOT_CANCELLABLE` with `details.status`. A message outside the key's inbox scope answers 404. Send an
     * empty JSON object `{}` as the body.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function cancel(
        string $id,
        ?RequestOptions $requestOptions = null,
    ): Models\Message {
        $response = $this->connection->request('messages.cancel', 'POST', '/v1/messages/'.rawurlencode($id).'/cancel', options: $requestOptions);

        return Models\Message::fromArray($response->json(), $response->meta());
    }

    /**
     * Retry a message.
     *
     * Re-queues the same message (same id, `reference_id` and conversation position) from `failed` or
     * `expired`. Requires `messages:send`. `attempts` is reset, the failure fields are cleared and
     * `expires_at` is set to now plus the lane default. Compliance and channel and account state are checked
     * again. `acknowledge_possible_duplicate` must be `true` to retry a message whose `failure.code` is
     * `UNCONFIRMED`; it is ignored otherwise. Errors: `409 NOT_RETRYABLE` (`details.status`,
     * `details.failure_code`; not `failed` or `expired`, inbound, or `UNCONFIRMED` without acknowledgement),
     * `409 RETRY_LIMIT_REACHED` (5 manual retries), and `422` `RECIPIENT_OPTED_OUT`, `CONTACT_BLOCKED`,
     * `CHANNEL_INACTIVE` or `INBOX_ARCHIVED`. A message outside the key's inbox scope answers 404. Send `{}`
     * when there is nothing to acknowledge.
     *
     * @param  ?bool  $acknowledge_possible_duplicate  Must be `true` to retry a message that failed with `UNCONFIRMED` (it may already have been delivered).
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function retry(
        string $id,
        ?bool $acknowledge_possible_duplicate = null,
        ?RequestOptions $requestOptions = null,
    ): Models\Message {
        $response = $this->connection->request(
            'messages.retry',
            'POST',
            '/v1/messages/'.rawurlencode($id).'/retry',
            body: Params::body([
                'acknowledge_possible_duplicate' => $acknowledge_possible_duplicate ?? Omit::Value,
            ]),
            options: $requestOptions,
        );

        return Models\Message::fromArray($response->json(), $response->meta());
    }

    /**
     * Send up to 1,000 messages.
     *
     * Sends 1-1,000 messages in one call. Requires `messages:send`. Each item has the fields of `POST
     * /v1/messages` except `dry_run`; `defaults` supplies any field an item omits and the item's value wins.
     * `category` is required on every item, in the item or in `defaults` (there is no implicit `transactional`
     * here).
     *
     * Items are accepted or rejected independently, in order: the answer is always `200` with a `summary` and
     * one `results[]` entry per item, also when every item was rejected. An item error carries the code of the
     * matching single send (`INVALID_NUMBER`, `CATEGORY_REQUIRED`, `RECIPIENT_OPTED_OUT`, ...). A
     * `reference_id` already used by the account, or earlier in the same batch, rejects that item with
     * `REFERENCE_ID_EXISTS`.
     *
     * `Idempotency-Key` covers the whole batch: a repeat replays the stored `200`. `dry_run: true` runs every
     * item through the same checks and stores nothing; accepted entries then carry a message preview with `id`
     * and `status` null.
     *
     * Envelope problems answer `422` and nothing is processed: `BATCH_TOO_LARGE` (`details.max`, 1,000) or
     * `VALIDATION_FAILED`.
     *
     * @param  list<Inputs\SendBatchItem|array<string, mixed>>  $messages
     * @param  ?bool  $dry_run  Validate every item through the same checks without storing anything.
     * @param  Inputs\SendMessage|array<string, mixed>|null  $defaults  Supplies any field an item omits; the item's value wins.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function sendBatch(
        array $messages,
        ?bool $dry_run = null,
        Inputs\SendMessage|array|null $defaults = null,
        ?RequestOptions $requestOptions = null,
    ): Models\SendBatchResponse {
        $response = $this->connection->request(
            'messages.sendBatch',
            'POST',
            '/v1/messages/batch',
            body: Params::body([
                'dry_run' => $dry_run ?? Omit::Value,
                'defaults' => Params::object($defaults ?? Omit::Value),
                'messages' => $messages,
            ]),
            options: $requestOptions,
        );

        return Models\SendBatchResponse::fromArray($response->json(), $response->meta());
    }

    /**
     * List and search messages.
     *
     * Messages of the account, newest first, with cursor pagination. Requires `messages:read`. A restricted
     * key lists only the messages of its inboxes. Comma lists match any of the values. `q` is a full-text
     * search on `body` (at least 2 characters, Postgres `simple` configuration, so Urdu and Roman Urdu match
     * word by word) and always sorts newest first.
     *
     * @param  ?string  $direction  `outbound` or `inbound`.
     * @param  ?string  $reference_id  Exact match; returns 0 or 1 item.
     * @param  ?string  $status  Comma list.
     * @param  ?string  $type  Comma list.
     * @param  ?string  $category  Comma list.
     * @param  ?string  $lane  Comma list.
     * @param  ?string  $origin  Comma list.
     * @param  ?string  $failure_code  Comma list.
     * @param  ?string  $channel_type  Comma list.
     * @param  ?string  $content_type  Comma list.
     * @param  ?string  $hold_reason  Comma list.
     * @param  ?string  $to  Recipient address; phone numbers in any accepted format are normalised.
     * @param  ?string  $from  Sender address; phone numbers in any accepted format are normalised.
     * @param  ?string  $q  Full-text search on the body, at least 2 characters.
     * @param  DateTimeInterface|string|null  $created_after  Inclusive lower bound.
     * @param  DateTimeInterface|string|null  $created_before  Exclusive upper bound.
     * @param  ?string  $sort  `-created_at` (default) or `created_at`; ignored when `q` is given. One of: `-created_at`, `created_at`.
     * @param  ?int  $limit  Page size, 1 to 200.
     * @param  ?string  $cursor  Opaque cursor from a previous page's `next_cursor`; valid for 24 hours.
     * @return Page<Models\Message>
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function list(
        ?string $inbox_id = null,
        ?string $channel_id = null,
        ?string $conversation_id = null,
        ?string $contact_id = null,
        ?string $campaign_id = null,
        ?string $direction = null,
        ?string $reference_id = null,
        ?string $status = null,
        ?string $type = null,
        ?string $category = null,
        ?string $lane = null,
        ?string $origin = null,
        ?string $failure_code = null,
        ?string $channel_type = null,
        ?string $content_type = null,
        ?string $hold_reason = null,
        ?string $to = null,
        ?string $from = null,
        ?string $q = null,
        DateTimeInterface|string|null $created_after = null,
        DateTimeInterface|string|null $created_before = null,
        ?string $sort = null,
        ?int $limit = null,
        ?string $cursor = null,
        ?RequestOptions $requestOptions = null,
    ): Page {
        $response = $this->connection->request(
            'messages.list',
            'GET',
            '/v1/messages',
            query: Params::query([
                'inbox_id' => $inbox_id,
                'channel_id' => $channel_id,
                'conversation_id' => $conversation_id,
                'contact_id' => $contact_id,
                'campaign_id' => $campaign_id,
                'direction' => $direction,
                'reference_id' => $reference_id,
                'status' => $status,
                'type' => $type,
                'category' => $category,
                'lane' => $lane,
                'origin' => $origin,
                'failure_code' => $failure_code,
                'channel_type' => $channel_type,
                'content_type' => $content_type,
                'hold_reason' => $hold_reason,
                'to' => $to,
                'from' => $from,
                'q' => $q,
                'created_after' => $created_after,
                'created_before' => $created_before,
                'sort' => $sort,
                'limit' => $limit,
                'cursor' => $cursor,
            ]),
            options: $requestOptions,
        );

        return Page::fromResponse($response, static fn (array $item): Models\Message => Models\Message::fromArray($item), fn (string $next): Page => $this->list(inbox_id: $inbox_id, channel_id: $channel_id, conversation_id: $conversation_id, contact_id: $contact_id, campaign_id: $campaign_id, direction: $direction, reference_id: $reference_id, status: $status, type: $type, category: $category, lane: $lane, origin: $origin, failure_code: $failure_code, channel_type: $channel_type, content_type: $content_type, hold_reason: $hold_reason, to: $to, from: $from, q: $q, created_after: $created_after, created_before: $created_before, sort: $sort, limit: $limit, cursor: $next, requestOptions: $requestOptions));
    }

    /**
     * Every item of `list()`, fetching the following pages as the loop reaches them.
     *
     * `limit` is the page size, not a cap on the items.
     *
     * @param  ?string  $direction  `outbound` or `inbound`.
     * @param  ?string  $reference_id  Exact match; returns 0 or 1 item.
     * @param  ?string  $status  Comma list.
     * @param  ?string  $type  Comma list.
     * @param  ?string  $category  Comma list.
     * @param  ?string  $lane  Comma list.
     * @param  ?string  $origin  Comma list.
     * @param  ?string  $failure_code  Comma list.
     * @param  ?string  $channel_type  Comma list.
     * @param  ?string  $content_type  Comma list.
     * @param  ?string  $hold_reason  Comma list.
     * @param  ?string  $to  Recipient address; phone numbers in any accepted format are normalised.
     * @param  ?string  $from  Sender address; phone numbers in any accepted format are normalised.
     * @param  ?string  $q  Full-text search on the body, at least 2 characters.
     * @param  DateTimeInterface|string|null  $created_after  Inclusive lower bound.
     * @param  DateTimeInterface|string|null  $created_before  Exclusive upper bound.
     * @param  ?string  $sort  `-created_at` (default) or `created_at`; ignored when `q` is given. One of: `-created_at`, `created_at`.
     * @param  ?int  $limit  Page size, 1 to 200.
     * @return Generator<int, Models\Message>
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function all(
        ?string $inbox_id = null,
        ?string $channel_id = null,
        ?string $conversation_id = null,
        ?string $contact_id = null,
        ?string $campaign_id = null,
        ?string $direction = null,
        ?string $reference_id = null,
        ?string $status = null,
        ?string $type = null,
        ?string $category = null,
        ?string $lane = null,
        ?string $origin = null,
        ?string $failure_code = null,
        ?string $channel_type = null,
        ?string $content_type = null,
        ?string $hold_reason = null,
        ?string $to = null,
        ?string $from = null,
        ?string $q = null,
        DateTimeInterface|string|null $created_after = null,
        DateTimeInterface|string|null $created_before = null,
        ?string $sort = null,
        ?int $limit = null,
        ?RequestOptions $requestOptions = null,
    ): Generator {
        return $this->list(inbox_id: $inbox_id, channel_id: $channel_id, conversation_id: $conversation_id, contact_id: $contact_id, campaign_id: $campaign_id, direction: $direction, reference_id: $reference_id, status: $status, type: $type, category: $category, lane: $lane, origin: $origin, failure_code: $failure_code, channel_type: $channel_type, content_type: $content_type, hold_reason: $hold_reason, to: $to, from: $from, q: $q, created_after: $created_after, created_before: $created_before, sort: $sort, limit: $limit, requestOptions: $requestOptions)->autoPaging();
    }

    /**
     * Send one message.
     *
     * Queues one message. Requires `messages:send`. The inbox decides the channel: give `inbox_id`,
     * `channel_id` or `from` (the key's single inbox is used when it is restricted to exactly one). The
     * recipient is `to` or `contact_id`. The content is the `content` union or one of the shorthand fields
     * (`body`, `media_ids`).
     *
     * Answers `202` with the message resource (`status: queued`) plus `warnings[]` (empty when none; for
     * example `CHANNEL_OPTIONS_IGNORED`). A message is accepted while the phone is offline or the channel is
     * paused; it waits with `hold_reason` `device_offline`, `device_paused` or `channel_paused`.
     *
     * `Idempotency-Key` replays the stored `202` for the same request. A `reference_id` already used by the
     * account without a matching key answers `409 REFERENCE_ID_EXISTS` with `details.message_id` and the
     * existing message in `details.message`; nothing is created. Every acceptance rejection of 02 section 6.4
     * answers `422` with its own code (`CATEGORY_REQUIRED`, `SEND_AFTER_TOO_FAR`, `INVALID_NUMBER`,
     * `RECIPIENT_OPTED_OUT`, `CHANNEL_INACTIVE`, ...) and the `details` of 03 section 2.6.1. Shape errors
     * (including `metadata` over 2 KB or 50 keys, or non-scalar values) answer `422 VALIDATION_FAILED` with
     * `details.fields`. An inbox, channel or contact outside the key's scope answers 404.
     *
     * With `dry_run: true` the same validation and acceptance errors apply, but nothing is stored, queued or
     * sent and the answer is `200` with the would-be message: `dry_run: true`, `id`, `conversation_id`,
     * `status` and the timestamps `null`, `contact_id` `null` for a new number, and the computed `encoding`,
     * `segments`, `type`, `hold_reason` and `expires_at`. `hold_reason` predicts compliance and scheduling
     * holds only. `Idempotency-Key` and `reference_id` are checked but not consumed.
     *
     * @param  ?string  $from  A channel address; resolves to that channel's main inbox.
     * @param  ?string  $to  Recipient address in the channel's format (a phone number in any accepted format for SMS).
     * @param  array<string, mixed>|null  $content  The content union. `type` is `text`, `media`, `template` or a seam type refused with `CHANNEL_CAPABILITY_UNSUPPORTED`.
     * @param  list<string>|null  $media_ids
     * @param  array<string, mixed>|null  $variables
     * @param  array<string, mixed>|null  $channel_options
     * @param  Enums\MessageCategory|string|null  $category  Required when `lane` is `bulk` (`CATEGORY_REQUIRED`).
     * @param  DateTimeInterface|string|null  $send_after  At most 30 days ahead (`SEND_AFTER_TOO_FAR`). Past values mean now.
     * @param  ?string  $reference_id  Unique per account forever.
     * @param  array<string, mixed>|null  $metadata  At most 2 KB as JSON; scalar values only.
     * @param  ?bool  $dry_run  Validate, render and compute segments and holds without creating anything (answers `200`).
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function send(
        ?string $inbox_id = null,
        ?string $channel_id = null,
        ?string $from = null,
        ?string $channel_type = null,
        ?string $to = null,
        ?string $contact_id = null,
        ?array $content = null,
        ?string $body = null,
        ?array $media_ids = null,
        ?string $template_id = null,
        ?array $variables = null,
        ?array $channel_options = null,
        ?bool $smart_replace = null,
        Enums\MessageCategory|string|null $category = null,
        Enums\MessageLane|string|null $lane = null,
        DateTimeInterface|string|null $send_after = null,
        ?int $expires_in = null,
        ?string $reference_id = null,
        ?array $metadata = null,
        ?bool $shorten_links = null,
        ?bool $dry_run = null,
        ?RequestOptions $requestOptions = null,
    ): Models\SendMessageDryRunResponse|Models\SendMessageResponse {
        $response = $this->connection->request(
            'messages.send',
            'POST',
            '/v1/messages',
            body: Params::body([
                'inbox_id' => $inbox_id ?? Omit::Value,
                'channel_id' => $channel_id ?? Omit::Value,
                'from' => $from ?? Omit::Value,
                'channel_type' => $channel_type ?? Omit::Value,
                'to' => $to ?? Omit::Value,
                'contact_id' => $contact_id ?? Omit::Value,
                'content' => Params::object($content ?? Omit::Value),
                'body' => $body ?? Omit::Value,
                'media_ids' => $media_ids ?? Omit::Value,
                'template_id' => $template_id ?? Omit::Value,
                'variables' => Params::object($variables ?? Omit::Value),
                'channel_options' => Params::object($channel_options ?? Omit::Value),
                'smart_replace' => $smart_replace ?? Omit::Value,
                'category' => $category ?? Omit::Value,
                'lane' => $lane ?? Omit::Value,
                'send_after' => $send_after ?? Omit::Value,
                'expires_in' => $expires_in ?? Omit::Value,
                'reference_id' => $reference_id ?? Omit::Value,
                'metadata' => Params::object($metadata ?? Omit::Value),
                'shorten_links' => $shorten_links ?? Omit::Value,
                'dry_run' => $dry_run ?? Omit::Value,
            ]),
            options: $requestOptions,
        );

        return match ($response->status) {
            200 => Models\SendMessageDryRunResponse::fromArray($response->json(), $response->meta()),
            default => Models\SendMessageResponse::fromArray($response->json(), $response->meta()),
        };
    }

    /**
     * Retrieve a message.
     *
     * Returns the message resource, inbound or outbound. Requires `messages:read`. A message outside the key's
     * inbox scope answers 404.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function show(
        string $id,
        ?RequestOptions $requestOptions = null,
    ): Models\Message {
        $response = $this->connection->request('messages.show', 'GET', '/v1/messages/'.rawurlencode($id), options: $requestOptions);

        return Models\Message::fromArray($response->json(), $response->meta());
    }

    /**
     * Message timeline.
     *
     * The message's events from `message_events`, oldest first, not paginated. Requires `messages:read`. A
     * message outside the key's inbox scope answers 404.
     *
     * @return Page<Models\MessageEvent>
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function events(
        string $id,
        ?RequestOptions $requestOptions = null,
    ): Page {
        $response = $this->connection->request('messages.events', 'GET', '/v1/messages/'.rawurlencode($id).'/events', options: $requestOptions);

        return Page::fromResponse($response, static fn (array $item): Models\MessageEvent => Models\MessageEvent::fromArray($item), null);
    }
}
