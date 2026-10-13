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
 * The body of `POST /v1/messages`. Unknown fields are ignored.
 */
final class SendMessage extends Input
{
    /**
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
        public readonly ?bool $dry_run = null,
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
            'dry_run' => $this->dry_run ?? Omit::Value,
        ]);
    }
}
