<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

final class ManagedAccountCounts extends Model
{
    public readonly int $devices_active;

    public readonly int $devices_online;

    public readonly int $channels_active;

    /**
     * Channels `paused` or `disconnected`, with a SIM safety band `poor` or `critical`, or whose device is `offline`.
     */
    public readonly int $channels_needing_attention;

    /**
     * @var array<string, int>
     */
    public readonly array $channels_by_type;

    protected function hydrate(array $data): void
    {
        $this->devices_active = $data['devices_active'];
        $this->devices_online = $data['devices_online'];
        $this->channels_active = $data['channels_active'];
        $this->channels_needing_attention = $data['channels_needing_attention'];
        $this->channels_by_type = $data['channels_by_type'];
    }
}
