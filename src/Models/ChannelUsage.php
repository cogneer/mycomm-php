<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

/**
 * Sent segments in rolling windows.
 */
final class ChannelUsage extends Model
{
    public readonly int $segments_15m;

    public readonly int $segments_1h;

    public readonly int $segments_24h;

    protected function hydrate(array $data): void
    {
        $this->segments_15m = $data['segments_15m'];
        $this->segments_1h = $data['segments_1h'];
        $this->segments_24h = $data['segments_24h'];
    }
}
