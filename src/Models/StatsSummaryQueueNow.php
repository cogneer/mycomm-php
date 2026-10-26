<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

final class StatsSummaryQueueNow extends Model
{
    public readonly int $queued;

    public readonly int $held;

    public readonly int $leased;

    protected function hydrate(array $data): void
    {
        $this->queued = $data['queued'];
        $this->held = $data['held'];
        $this->leased = $data['leased'];
    }
}
