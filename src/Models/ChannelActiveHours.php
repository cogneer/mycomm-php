<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

/**
 * The window in which the channel sends; `null` means any time.
 */
final class ChannelActiveHours extends Model
{
    public readonly ?string $start;

    public readonly ?string $end;

    public readonly ?string $timezone;

    protected function hydrate(array $data): void
    {
        $this->start = $data['start'] ?? null;
        $this->end = $data['end'] ?? null;
        $this->timezone = $data['timezone'] ?? null;
    }
}
