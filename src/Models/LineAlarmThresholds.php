<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

/**
 * When the line's alarms fire (`line.alarm`). `budget_percent` (50 to 100, default 80): a sender of the
 * line used this share of its hourly limit; for a sender on several lines the lowest value counts.
 * `self_loop_per_hour` (5 to 500, default 20): a sender of the line sent this many messages to the
 * account's own numbers within an hour. A value above the default warns later than advised.
 */
final class LineAlarmThresholds extends Model
{
    public readonly int $budget_percent;

    public readonly int $self_loop_per_hour;

    protected function hydrate(array $data): void
    {
        $this->budget_percent = $data['budget_percent'];
        $this->self_loop_per_hour = $data['self_loop_per_hour'];
    }
}
