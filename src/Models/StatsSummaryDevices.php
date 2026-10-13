<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

final class StatsSummaryDevices extends Model
{
    public readonly int $online;

    public readonly int $degraded;

    public readonly int $offline;

    protected function hydrate(array $data): void
    {
        $this->online = $data['online'];
        $this->degraded = $data['degraded'];
        $this->offline = $data['offline'];
    }
}
