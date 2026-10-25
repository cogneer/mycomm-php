<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Inputs;

use DateTimeInterface;
use MyComm\Enums;
use MyComm\Http\Params;
use MyComm\Input;
use MyComm\Omit;

/**
 * An item of a batch: the sender, recipient, content and option fields of `SendMessageRequest`, without
 * `dry_run` (the batch has its own). Fields the item omits come from `defaults`; the rules of
 * `SendMessageRequest` (a recipient, some content, `category` on the bulk lane) apply to the item after
 * the defaults are merged in. A batch item always needs a `category`, in the item or in `defaults`.
 */
final class SendBatchItem extends Input
{
    /**
     * @param  ?string  $inbox_id  The inbox to send from. A key limited to one inbox may leave all sender fields out.
     * @param  ?string  $channel_id  Send from this channel's main inbox.
     * @param  ?string  $from  A channel address (for SMS, the number); resolves to that channel's main inbox.
     * @param  ?string  $channel_type  Only to choose between channels of different types that share the `from` address.
     * @param  ?string  $to  Recipient address in the channel's format (a phone number in any accepted format for SMS). Not together with `contact_id`.
     * @param  ?string  $contact_id  Send to this contact's address for the channel. Not together with `to`.
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
     */
    public function __construct(
        public readonly ?string $inbox_id = null,
        public readonly ?string $channel_id = null,
        public readonly ?string $from = null,
        public readonly ?string $channel_type = null,
        public readonly ?string $to = null,
        public readonly ?string $contact_id = null,
        public readonly ?array $content = null,
        public readonly ?string $body = null,
        public readonly ?array $media_ids = null,
        public readonly ?string $template_id = null,
        public readonly ?array $variables = null,
        public readonly ?array $channel_options = null,
        public readonly ?bool $smart_replace = null,
        public readonly Enums\MessageCategory|string|null $category = null,
        public readonly Enums\MessageLane|string|null $lane = null,
        public readonly DateTimeInterface|string|null $send_after = null,
        public readonly ?int $expires_in = null,
        public readonly ?string $reference_id = null,
        public readonly ?array $metadata = null,
        public readonly ?bool $shorten_links = null,
    ) {}

    public function toArray(): array
    {
        return Params::body([
            'inbox_id' => $this->inbox_id ?? Omit::Value,
            'channel_id' => $this->channel_id ?? Omit::Value,
            'from' => $this->from ?? Omit::Value,
            'channel_type' => $this->channel_type ?? Omit::Value,
            'to' => $this->to ?? Omit::Value,
            'contact_id' => $this->contact_id ?? Omit::Value,
            'content' => Params::object($this->content ?? Omit::Value),
            'body' => $this->body ?? Omit::Value,
            'media_ids' => $this->media_ids ?? Omit::Value,
            'template_id' => $this->template_id ?? Omit::Value,
            'variables' => Params::object($this->variables ?? Omit::Value),
            'channel_options' => Params::object($this->channel_options ?? Omit::Value),
            'smart_replace' => $this->smart_replace ?? Omit::Value,
            'category' => $this->category ?? Omit::Value,
            'lane' => $this->lane ?? Omit::Value,
            'send_after' => $this->send_after ?? Omit::Value,
            'expires_in' => $this->expires_in ?? Omit::Value,
            'reference_id' => $this->reference_id ?? Omit::Value,
            'metadata' => Params::object($this->metadata ?? Omit::Value),
            'shorten_links' => $this->shorten_links ?? Omit::Value,
        ]);
    }
}
