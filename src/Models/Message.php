<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

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
use MyComm\Omit;
use MyComm\Page;
use MyComm\RequestOptions;

/**
 * The message resource. Optional values are `null`, never omitted. Enum-like strings may gain values.
 */
final class Message extends Model
{
    public readonly string $id;

    public readonly string $account_id;

    public readonly string $channel_id;

    /**
     * The channel's type, `android_sim` in v1.
     */
    public readonly ?string $channel_type;

    public readonly string $inbox_id;

    public readonly ?string $conversation_id;

    public readonly ?string $contact_id;

    public readonly ?MessageIdentity $identity;

    /**
     * `outbound` or `inbound`.
     */
    public readonly string $direction;

    /**
     * `sms` or `mms` (later `whatsapp`, `telegram`).
     */
    public readonly string $type;

    /**
     * `portal`, `api`, `campaign`, `automation`, `phone`, `import`, `system` or `canary`.
     */
    public readonly string $origin;

    public readonly ?string $from;

    public readonly ?string $to;

    public readonly string $content_type;

    /**
     * The typed content object of 03 section 3.1; always has `type`.
     *
     * @var array<string, mixed>
     */
    public readonly array $content;

    public readonly ?string $body;

    /**
     * Outbound SMS only.
     */
    public readonly ?string $encoding;

    /**
     * Outbound SMS only.
     */
    public readonly ?int $segments;

    /**
     * @var list<MessageMedia>
     */
    public readonly array $media;

    /**
     * @var array<string, mixed>
     */
    public readonly array $channel_meta;

    public readonly ?string $provider_message_id;

    public readonly ?string $category;

    public readonly ?string $lane;

    /**
     * Outbound: `queued`, `leased`, `sent`, `delivered`, `read`, `failed`, `expired`, `cancelled`. Inbound: `received`.
     */
    public readonly string $status;

    public readonly ?string $hold_reason;

    public readonly ?DateTimeImmutable $send_after;

    public readonly ?DateTimeImmutable $expires_at;

    public readonly int $attempts;

    public readonly ?MessageFailure $failure;

    public readonly ?DateTimeImmutable $cancel_requested_at;

    public readonly ?string $reference_id;

    /**
     * @var array<string, mixed>
     */
    public readonly array $metadata;

    public readonly ?string $template_id;

    public readonly ?string $campaign_id;

    public readonly MessageActor $actor;

    public readonly DateTimeImmutable $created_at;

    public readonly ?DateTimeImmutable $sent_at;

    public readonly ?DateTimeImmutable $delivered_at;

    public readonly ?DateTimeImmutable $failed_at;

    public readonly ?DateTimeImmutable $expired_at;

    public readonly ?DateTimeImmutable $cancelled_at;

    public readonly ?DateTimeImmutable $received_at;

    public readonly ?DateTimeImmutable $read_at;

    public readonly ?DateTimeImmutable $phone_timestamp;

    protected function hydrate(array $data): void
    {
        $this->id = $data['id'];
        $this->account_id = $data['account_id'];
        $this->channel_id = $data['channel_id'];
        $this->channel_type = $data['channel_type'] ?? null;
        $this->inbox_id = $data['inbox_id'];
        $this->conversation_id = $data['conversation_id'] ?? null;
        $this->contact_id = $data['contact_id'] ?? null;
        $this->identity = isset($data['identity']) ? MessageIdentity::fromArray($data['identity']) : null;
        $this->direction = $data['direction'];
        $this->type = $data['type'];
        $this->origin = $data['origin'];
        $this->from = $data['from'] ?? null;
        $this->to = $data['to'] ?? null;
        $this->content_type = $data['content_type'];
        $this->content = $data['content'];
        $this->body = $data['body'] ?? null;
        $this->encoding = $data['encoding'] ?? null;
        $this->segments = $data['segments'] ?? null;
        $this->media = array_map(static fn (array $item): MessageMedia => MessageMedia::fromArray($item), array_values($data['media']));
        $this->channel_meta = $data['channel_meta'];
        $this->provider_message_id = $data['provider_message_id'] ?? null;
        $this->category = $data['category'] ?? null;
        $this->lane = $data['lane'] ?? null;
        $this->status = $data['status'];
        $this->hold_reason = $data['hold_reason'] ?? null;
        $this->send_after = isset($data['send_after']) ? self::dateTime($data['send_after']) : null;
        $this->expires_at = isset($data['expires_at']) ? self::dateTime($data['expires_at']) : null;
        $this->attempts = $data['attempts'];
        $this->failure = isset($data['failure']) ? MessageFailure::fromArray($data['failure']) : null;
        $this->cancel_requested_at = isset($data['cancel_requested_at']) ? self::dateTime($data['cancel_requested_at']) : null;
        $this->reference_id = $data['reference_id'] ?? null;
        $this->metadata = $data['metadata'];
        $this->template_id = $data['template_id'] ?? null;
        $this->campaign_id = $data['campaign_id'] ?? null;
        $this->actor = MessageActor::fromArray($data['actor']);
        $this->created_at = self::dateTime($data['created_at']);
        $this->sent_at = isset($data['sent_at']) ? self::dateTime($data['sent_at']) : null;
        $this->delivered_at = isset($data['delivered_at']) ? self::dateTime($data['delivered_at']) : null;
        $this->failed_at = isset($data['failed_at']) ? self::dateTime($data['failed_at']) : null;
        $this->expired_at = isset($data['expired_at']) ? self::dateTime($data['expired_at']) : null;
        $this->cancelled_at = isset($data['cancelled_at']) ? self::dateTime($data['cancelled_at']) : null;
        $this->received_at = isset($data['received_at']) ? self::dateTime($data['received_at']) : null;
        $this->read_at = isset($data['read_at']) ? self::dateTime($data['read_at']) : null;
        $this->phone_timestamp = isset($data['phone_timestamp']) ? self::dateTime($data['phone_timestamp']) : null;
    }
}
