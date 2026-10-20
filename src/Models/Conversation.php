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
 * The conversation resource: one per inbox and contact identity. `address`, `contact_display_name`,
 * `suppressed` and `blocked_at` are denormalised from the identity; blocking is account-wide.
 */
final class Conversation extends Model
{
    public readonly string $id;

    public readonly string $inbox_id;

    public readonly string $channel_id;

    /**
     * The channel's type, `android_sim` in v1.
     */
    public readonly ?string $channel_type;

    public readonly string $contact_id;

    public readonly string $contact_identity_id;

    /**
     * The contact identity's address (E.164 for a phone).
     */
    public readonly ?string $address;

    public readonly ?string $contact_display_name;

    /**
     * `open` or `archived`; new values may appear.
     */
    public readonly string $status;

    public readonly int $unread_count;

    public readonly ?string $last_message_id;

    public readonly ?DateTimeImmutable $last_message_at;

    /**
     * First 120 characters of the last message.
     */
    public readonly ?string $last_message_preview;

    /**
     * `inbound` or `outbound`.
     */
    public readonly ?string $last_direction;

    public readonly ?DateTimeImmutable $last_outbound_at;

    public readonly ?DateTimeImmutable $last_inbound_at;

    public readonly ?DateTimeImmutable $blocked_at;

    /**
     * The identity is on the suppression list.
     */
    public readonly bool $suppressed;

    /**
     * @var array<string, mixed>
     */
    public readonly array $channel_meta;

    public readonly DateTimeImmutable $created_at;

    protected function hydrate(array $data): void
    {
        $this->id = $data['id'];
        $this->inbox_id = $data['inbox_id'];
        $this->channel_id = $data['channel_id'];
        $this->channel_type = $data['channel_type'] ?? null;
        $this->contact_id = $data['contact_id'];
        $this->contact_identity_id = $data['contact_identity_id'];
        $this->address = $data['address'] ?? null;
        $this->contact_display_name = $data['contact_display_name'] ?? null;
        $this->status = $data['status'];
        $this->unread_count = $data['unread_count'];
        $this->last_message_id = $data['last_message_id'] ?? null;
        $this->last_message_at = isset($data['last_message_at']) ? self::dateTime($data['last_message_at']) : null;
        $this->last_message_preview = $data['last_message_preview'] ?? null;
        $this->last_direction = $data['last_direction'] ?? null;
        $this->last_outbound_at = isset($data['last_outbound_at']) ? self::dateTime($data['last_outbound_at']) : null;
        $this->last_inbound_at = isset($data['last_inbound_at']) ? self::dateTime($data['last_inbound_at']) : null;
        $this->blocked_at = isset($data['blocked_at']) ? self::dateTime($data['blocked_at']) : null;
        $this->suppressed = $data['suppressed'];
        $this->channel_meta = $data['channel_meta'];
        $this->created_at = self::dateTime($data['created_at']);
    }
}
