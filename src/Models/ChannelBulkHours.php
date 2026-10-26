<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

/**
 * The window in which the `bulk` lane may send; `tz` `null` means the channel country's time zone.
 */
final class ChannelBulkHours extends Model
{
    public readonly ?string $start;

    public readonly ?string $end;

    public readonly ?string $tz;

    protected function hydrate(array $data): void
    {
        $this->start = $data['start'] ?? null;
        $this->end = $data['end'] ?? null;
        $this->tz = $data['tz'] ?? null;
    }
}
