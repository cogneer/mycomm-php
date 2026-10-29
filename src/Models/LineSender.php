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
        $this->healthy = $data['healthy'];
    }
}
