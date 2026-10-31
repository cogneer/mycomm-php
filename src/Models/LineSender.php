<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Enums;
use MyComm\Model;

/**
 * One entry of a line's sender list.
 */
final class LineSender extends Model
{
    public readonly string $channel_id;

    public readonly Enums\ChannelType|string $channel_type;

    /**
     * The channel's address (for SMS, the number).
     */
    public readonly string $address;

    public readonly Enums\LineSenderRole|string $role;

    /**
     * 0 is tried first.
     */
    public readonly int $position;

    /**
     * A disabled sender stays in the list but never sends.
     */
    public readonly bool $enabled;

    /**
     * The line gets a read-only copy of every new inbound on this channel that lands on another line: one more `message.received` with the envelope `line_id` of this line and `data.mirrored: true`. No conversation or message…
     */
    public readonly bool $mirror_inbound;

    /**
     * Active, its phone online, not throttled, and with hourly and daily budget left. Changes from moment to moment.
     */
    public readonly bool $healthy;

    protected function hydrate(array $data): void
    {
        $this->channel_id = $data['channel_id'];
        $this->channel_type = self::enum(Enums\ChannelType::class, $data['channel_type']);
        $this->address = $data['address'];
        $this->role = self::enum(Enums\LineSenderRole::class, $data['role']);
        $this->position = $data['position'];
        $this->enabled = $data['enabled'];
        $this->mirror_inbound = $data['mirror_inbound'];
        $this->healthy = $data['healthy'];
    }
}
