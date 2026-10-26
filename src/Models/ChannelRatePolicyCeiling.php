<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

/**
 * The most each cap may be set to for this channel now (`rate_policy_ceiling`).
 */
final class ChannelRatePolicyCeiling extends Model
{
    public readonly int $per_min;

    public readonly int $per_15m;

    public readonly int $per_hour;

    public readonly int $per_day;

    protected function hydrate(array $data): void
    {
        $this->per_min = $data['per_min'];
        $this->per_15m = $data['per_15m'];
        $this->per_hour = $data['per_hour'];
        $this->per_day = $data['per_day'];
    }
}
