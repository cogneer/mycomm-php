<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Resources;

use DateTimeInterface;
use Generator;
use MyComm\Enums;
use MyComm\Exceptions\ApiException;
use MyComm\Exceptions\ConnectionException;
use MyComm\Exceptions\InvalidArgumentException;
use MyComm\Http\Connection;
use MyComm\Http\Params;
use MyComm\Models;
use MyComm\Omit;
use MyComm\Page;
use MyComm\RequestOptions;

final class Conversations
{
    public function __construct(private readonly Connection $connection) {}

    /**
     * Mark a conversation read.
     *
     * Sets `unread_count` to 0 and stamps `read_at` on its unread inbound messages. Emits `conversation.read`.
     * Requires `conversations:write`. A conversation outside the key's inbox scope answers 404.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function read(
        string $id,
        ?RequestOptions $requestOptions = null,
    ): Models\Conversation {
        $response = $this->connection->request('conversations.read', 'POST', '/v1/conversations/'.rawurlencode($id).'/read', options: $requestOptions);

        return Models\Conversation::fromArray($response->json(), $response->meta());
    }

    /**
     * Mark a conversation unread.
     *
     * Sets `unread_count` to 1. Emits `conversation.unread`. Requires `conversations:write`. A conversation
     * outside the key's inbox scope answers 404.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function unread(
        string $id,
        ?RequestOptions $requestOptions = null,
    ): Models\Conversation {
        $response = $this->connection->request('conversations.unread', 'POST', '/v1/conversations/'.rawurlencode($id).'/unread', options: $requestOptions);

        return Models\Conversation::fromArray($response->json(), $response->meta());
    }

    /**
     * Archive a conversation.
     *
     * Sets `status` to `archived`; the next inbound message reopens it. Emits `conversation.archived`.
     * Requires `conversations:write`. A conversation outside the key's inbox scope answers 404.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function archive(
        string $id,
        ?RequestOptions $requestOptions = null,
    ): Models\Conversation {
        $response = $this->connection->request('conversations.archive', 'POST', '/v1/conversations/'.rawurlencode($id).'/archive', options: $requestOptions);

        return Models\Conversation::fromArray($response->json(), $response->meta());
    }

    /**
     * Unarchive a conversation.
     *
     * Sets `status` back to `open`. Emits `conversation.unarchived`. Requires `conversations:write`. A
     * conversation outside the key's inbox scope answers 404.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function unarchive(
        string $id,
        ?RequestOptions $requestOptions = null,
    ): Models\Conversation {
        $response = $this->connection->request('conversations.unarchive', 'POST', '/v1/conversations/'.rawurlencode($id).'/unarchive', options: $requestOptions);

        return Models\Conversation::fromArray($response->json(), $response->meta());
    }

    /**
     * Block the conversation's contact identity.
     *
     * Convenience route: blocks the conversation's contact identity account-wide (every inbox and channel),
     * exactly like `POST /v1/contacts/{id}/identities/{identity_id}/block`. Idempotent. Every conversation of
     * that identity shows the same `blocked_at`. Requires `conversations:write`. A conversation outside the
     * key's inbox scope answers 404.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function block(
        string $id,
        ?RequestOptions $requestOptions = null,
    ): Models\Conversation {
        $response = $this->connection->request('conversations.block', 'POST', '/v1/conversations/'.rawurlencode($id).'/block', options: $requestOptions);

        return Models\Conversation::fromArray($response->json(), $response->meta());
    }

    /**
     * Unblock the conversation's contact identity.
     *
     * Clears `blocked_at` of the conversation's contact identity, account-wide. Nothing is replayed. Requires
     * `conversations:write`. A conversation outside the key's inbox scope answers 404.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function unblock(
        string $id,
        ?RequestOptions $requestOptions = null,
    ): Models\Conversation {
        $response = $this->connection->request('conversations.unblock', 'POST', '/v1/conversations/'.rawurlencode($id).'/unblock', options: $requestOptions);

        return Models\Conversation::fromArray($response->json(), $response->meta());
    }

    /**
     * Retrieve a conversation.
     *
     * Returns the conversation resource. Requires `conversations:read`. A conversation outside the key's inbox
     * scope answers 404.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function show(
        string $id,
        ?RequestOptions $requestOptions = null,
    ): Models\Conversation {
        $response = $this->connection->request('conversations.show', 'GET', '/v1/conversations/'.rawurlencode($id), options: $requestOptions);

        return Models\Conversation::fromArray($response->json(), $response->meta());
    }

    /**
     * Delete a conversation.
     *
     * Permanently deletes the conversation with its messages, their events, media and short links. Requires
     * `conversations:write`. Queued outbound messages are cancelled first; the purge runs in the background
     * and emits `conversation.deleted` when it is complete. Answers `202` at once; the conversation is no
     * longer readable. A conversation outside the key's inbox scope answers 404.
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function delete(
        string $id,
        ?RequestOptions $requestOptions = null,
    ): Models\ConversationsDeleteResponse {
        $response = $this->connection->request('conversations.delete', 'DELETE', '/v1/conversations/'.rawurlencode($id), options: $requestOptions);

        return Models\ConversationsDeleteResponse::fromArray($response->json(), $response->meta());
    }

    /**
     * List conversations.
     *
     * Conversations of the account, newest `last_message_at` first, with cursor pagination. Requires
     * `conversations:read`. A restricted key lists only the conversations of its inboxes. A conversation
     * without messages is not listed. Comma lists match any of the values. `q` matches the contact name,
     * address and last message preview (case-insensitive, at least 2 characters).
     *
     * @param  list<string>|string|null  $channel_type  Comma list.
     * @param  ?string  $status  `open` (default), `archived` or `all`. One of: `open`, `archived`, `all`.
     * @param  ?bool  $unread  `true` = `unread_count > 0`, `false` = read only.
     * @param  ?bool  $blocked  `true` = the identity is blocked, `false` = not blocked.
     * @param  ?string  $q  Contact name, address or last message preview, at least 2 characters.
     * @param  DateTimeInterface|string|null  $last_message_after  Inclusive lower bound.
     * @param  DateTimeInterface|string|null  $last_message_before  Exclusive upper bound.
     * @param  ?string  $sort  `-last_message_at` (default) or `last_message_at`. One of: `-last_message_at`, `last_message_at`.
     * @param  ?int  $limit  Page size, 1 to 200.
     * @param  ?string  $cursor  Opaque cursor from a previous page's `next_cursor`; valid for 24 hours.
     * @return Page<Models\Conversation>
     *
     * @throws InvalidArgumentException when an argument breaks a rule the API documents; nothing is sent (client option `validate`)
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function list(
        ?string $inbox_id = null,
        ?string $channel_id = null,
        array|string|null $channel_type = null,
        ?string $contact_id = null,
        ?string $status = null,
        ?bool $unread = null,
        ?bool $blocked = null,
        ?string $q = null,
        DateTimeInterface|string|null $last_message_after = null,
        DateTimeInterface|string|null $last_message_before = null,
        ?string $sort = null,
        ?int $limit = null,
        ?string $cursor = null,
        ?RequestOptions $requestOptions = null,
    ): Page {
        $this->connection->guard(
            'conversations.list',
            [
                'inbox_id' => $inbox_id,
                'channel_id' => $channel_id,
                'contact_id' => $contact_id,
                'q' => $q,
                'limit' => $limit,
            ],
            [
                'inbox_id' => [
                    'uuid' => true,
                ],
                'channel_id' => [
                    'uuid' => true,
                ],
                'contact_id' => [
                    'uuid' => true,
                ],
                'q' => [
                    'minLength' => 2,
                ],
                'limit' => [
                    'min' => 1,
                    'max' => 200,
                ],
            ],
            [],
        );

        $response = $this->connection->request(
            'conversations.list',
            'GET',
            '/v1/conversations',
            query: Params::query([
                'inbox_id' => $inbox_id,
                'channel_id' => $channel_id,
                'channel_type' => $channel_type,
                'contact_id' => $contact_id,
                'status' => $status,
                'unread' => $unread,
                'blocked' => $blocked,
                'q' => $q,
                'last_message_after' => $last_message_after,
                'last_message_before' => $last_message_before,
                'sort' => $sort,
                'limit' => $limit,
                'cursor' => $cursor,
            ]),
            options: $requestOptions,
        );

        return Page::fromResponse($response, static fn (array $item): Models\Conversation => Models\Conversation::fromArray($item), fn (string $next): Page => $this->list(inbox_id: $inbox_id, channel_id: $channel_id, channel_type: $channel_type, contact_id: $contact_id, status: $status, unread: $unread, blocked: $blocked, q: $q, last_message_after: $last_message_after, last_message_before: $last_message_before, sort: $sort, limit: $limit, cursor: $next, requestOptions: $requestOptions));
    }

    /**
     * Every item of `list()`, fetching the following pages as the loop reaches them.
     *
     * `limit` is the page size, not a cap on the items.
     *
     * @param  list<string>|string|null  $channel_type  Comma list.
     * @param  ?string  $status  `open` (default), `archived` or `all`. One of: `open`, `archived`, `all`.
     * @param  ?bool  $unread  `true` = `unread_count > 0`, `false` = read only.
     * @param  ?bool  $blocked  `true` = the identity is blocked, `false` = not blocked.
     * @param  ?string  $q  Contact name, address or last message preview, at least 2 characters.
     * @param  DateTimeInterface|string|null  $last_message_after  Inclusive lower bound.
     * @param  DateTimeInterface|string|null  $last_message_before  Exclusive upper bound.
     * @param  ?string  $sort  `-last_message_at` (default) or `last_message_at`. One of: `-last_message_at`, `last_message_at`.
     * @param  ?int  $limit  Page size, 1 to 200.
     * @return Generator<int, Models\Conversation>
     *
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function all(
        ?string $inbox_id = null,
        ?string $channel_id = null,
        array|string|null $channel_type = null,
        ?string $contact_id = null,
        ?string $status = null,
        ?bool $unread = null,
        ?bool $blocked = null,
        ?string $q = null,
        DateTimeInterface|string|null $last_message_after = null,
        DateTimeInterface|string|null $last_message_before = null,
        ?string $sort = null,
        ?int $limit = null,
        ?RequestOptions $requestOptions = null,
    ): Generator {
        return $this->list(inbox_id: $inbox_id, channel_id: $channel_id, channel_type: $channel_type, contact_id: $contact_id, status: $status, unread: $unread, blocked: $blocked, q: $q, last_message_after: $last_message_after, last_message_before: $last_message_before, sort: $sort, limit: $limit, requestOptions: $requestOptions)->autoPaging();
    }

    /**
     * Read a conversation's thread.
     *
     * The messages of one conversation, newest first by default. Requires `conversations:read`. Both sort
     * directions use `COALESCE(phone_timestamp, created_at)`, so imported history keeps the time the phone
     * recorded; `created_after` (inclusive) and `created_before` (exclusive) use the same time. Ties break on
     * `id`. `q` is the full-text search on `body` of `GET /v1/messages` (at least 2 characters). A
     * conversation outside the key's inbox scope answers 404.
     *
     * @param  list<string>|string|null  $direction  Comma list of `outbound`, `inbound`.
     * @param  ?string  $q  Full-text search on the body, at least 2 characters.
     * @param  DateTimeInterface|string|null  $created_after  Inclusive lower bound.
     * @param  DateTimeInterface|string|null  $created_before  Exclusive upper bound.
     * @param  ?string  $sort  `-created_at` (default) or `created_at`. One of: `-created_at`, `created_at`.
     * @param  ?int  $limit  Page size, 1 to 200.
     * @param  ?string  $cursor  Opaque cursor from a previous page's `next_cursor`; valid for 24 hours.
     * @return Page<Models\Message>
     *
     * @throws InvalidArgumentException when an argument breaks a rule the API documents; nothing is sent (client option `validate`)
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function messages(
        string $id,
        array|string|null $direction = null,
        ?string $q = null,
        DateTimeInterface|string|null $created_after = null,
        DateTimeInterface|string|null $created_before = null,
        ?string $sort = null,
        ?int $limit = null,
        ?string $cursor = null,
        ?RequestOptions $requestOptions = null,
    ): Page {
        $this->connection->guard(
            'conversations.messages',
            [
                'q' => $q,
                'limit' => $limit,
            ],
            [
                'q' => [
                    'minLength' => 2,
                ],
                'limit' => [
                    'min' => 1,
                    'max' => 200,
                ],
            ],
            [],
        );

        $response = $this->connection->request(
            'conversations.messages',
            'GET',
            '/v1/conversations/'.rawurlencode($id).'/messages',
            query: Params::query([
                'direction' => $direction,
                'q' => $q,
                'created_after' => $created_after,
                'created_before' => $created_before,
                'sort' => $sort,
                'limit' => $limit,
                'cursor' => $cursor,
            ]),
            options: $requestOptions,
        );

        return Page::fromResponse($response, static fn (array $item): Models\Message => Models\Message::fromArray($item), fn (string $next): Page => $this->messages(id: $id, direction: $direction, q: $q, created_after: $created_after, created_before: $created_before, sort: $sort, limit: $limit, cursor: $next, requestOptions: $requestOptions));
    }

    /**
     * Reply in a conversation.
     *
     * Sends a message in the thread. Requires `conversations:write` and `messages:send`. The body is the body
     * of `POST /v1/messages` without `to`, `contact_id`, `inbox_id`, `channel_id`, `from` and `channel_type`;
     * the inbox and contact identity of the conversation are used. Sending one of those fields answers `422
     * VALIDATION_FAILED` with the field in `details.fields`.
     *
     * Everything else behaves as on `POST /v1/messages`: `202` with the message resource plus `warnings[]`,
     * `dry_run`, `Idempotency-Key`, `reference_id` (`409 REFERENCE_ID_EXISTS`) and the `422` acceptance
     * rejections of `POST /v1/messages`. A conversation outside the key's inbox scope answers 404.
     *
     * @param  array<string, mixed>|null  $content  The full content form. `type` is `text`, `media`, `template` or a channel-specific type refused with `CHANNEL_CAPABILITY_UNSUPPORTED`. Excludes `body`, `media_ids`, `template_id` and `variables`.
     * @param  ?string  $body  Shorthand for text content. May go with `media_ids`.
     * @param  list<string>|null  $media_ids  Shorthand for media content: ids from `POST /v1/media`. One item today.
     * @param  ?string  $template_id  Shorthand for template content.
     * @param  array<string, mixed>|null  $variables  Values for the template's variables. Needs `template_id`.
     * @param  array<string, mixed>|null  $channel_options  Options only some channels understand; ignored with a warning elsewhere.
     * @param  ?bool  $smart_replace  Replace characters that force UCS-2 encoding with plain look-alikes where it saves segments.
     * @param  Enums\MessageCategory|string|null  $category  Required when `lane` is `bulk` (`CATEGORY_REQUIRED`).
     * @param  Enums\MessageLane|string|null  $lane  How urgently the message is sent. `bulk` is paced behind other traffic and needs `category`.
     * @param  DateTimeInterface|string|null  $send_after  At most 30 days ahead (`SEND_AFTER_TOO_FAR`). A time in the past means now.
     * @param  ?int  $expires_in  Seconds after which an unsent message is dropped, 60 to 604800 (7 days).
     * @param  ?string  $reference_id  Your own id for this message, 1 to 100 characters without spaces. Unique per account forever (`REFERENCE_ID_EXISTS`).
     * @param  array<string, mixed>|null  $metadata  Up to 50 keys, 2 KB as JSON, scalar values only. Returned on the message and in webhooks.
     * @param  ?bool  $shorten_links  Replace each URL with a short link so clicks are counted.
     * @param  ?bool  $dry_run  Validate, render and compute segments and holds without creating anything (answers `200`).
     *
     * @throws InvalidArgumentException when an argument breaks a rule the API documents; nothing is sent (client option `validate`)
     * @throws ApiException when the API answers with an error
     * @throws ConnectionException when the API cannot be reached
     */
    public function reply(
        string $id,
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
        $this->connection->guard(
            'conversations.reply',
            [
                'media_ids' => $media_ids,
                'template_id' => $template_id,
                'category' => $category,
                'lane' => $lane,
                'expires_in' => $expires_in,
                'reference_id' => $reference_id,
                'metadata' => $metadata,
                'content' => $content,
                'body' => $body,
                'variables' => $variables,
            ],
            [
                'media_ids' => [
                    'uuid' => true,
                ],
                'template_id' => [
                    'uuid' => true,
                ],
                'category' => [
                    'enum' => ['transactional', 'marketing'],
                ],
                'lane' => [
                    'enum' => ['high', 'normal', 'bulk'],
                ],
                'expires_in' => [
                    'min' => 60,
                    'max' => 604800,
                ],
                'reference_id' => [
                    'minLength' => 1,
                    'maxLength' => 100,
                    'pattern' => '^\\S+$',
                ],
                'metadata' => [
                    'maxProperties' => 50,
                    'maxBytes' => 2048,
                    'scalarValues' => true,
                ],
            ],
            [
                [
                    'atLeastOne',
                    ['content', 'body', 'media_ids', 'template_id'],
                ],
                ['requires', 'variables', 'template_id'],
                ['requiredWhen', 'category', 'lane', 'bulk'],
            ],
        );

        $response = $this->connection->request(
            'conversations.reply',
            'POST',
            '/v1/conversations/'.rawurlencode($id).'/messages',
            body: Params::body([
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
}
