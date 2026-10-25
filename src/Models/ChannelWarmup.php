<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use DateTimeImmutable;
use MyComm\Model;

/**
 * The warm-up of the number.
 */
final class ChannelWarmup extends Model
{
    public readonly bool $active;

    public readonly int $day;

    public readonly ?DateTimeImmutable $ends_at;

    public readonly ?int $today_cap;

    protected function hydrate(array $data): void
    {
        $this->active = $data['active'];
        $this->day = $data['day'];
        $this->ends_at = isset($data['ends_at']) ? self::dateTime($data['ends_at']) : null;
        $this->today_cap = $data['today_cap'] ?? null;
    }
}
