<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

final class WebhookChannelVolumeWarningData extends Model
{
    public readonly string $channel_id;

    public readonly string $window;

    public readonly int $used_segments;

    /**
     * The effective daily cap in segments.
     */
    public readonly int $cap;

    /**
     * The day the warning is for, `YYYY-MM-DD` in the channel's time zone.
     */
    public readonly string $date;

    protected function hydrate(array $data): void
    {
        $this->channel_id = $data['channel_id'];
        $this->window = $data['window'];
        $this->used_segments = $data['used_segments'];
        $this->cap = $data['cap'];
        $this->date = $data['date'];
    }
}
