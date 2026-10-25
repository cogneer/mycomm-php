<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

final class StatsSummaryRates extends Model
{
    public readonly ?float $delivery_rate;

    public readonly ?float $failure_rate;

    public readonly ?float $read_rate;

    protected function hydrate(array $data): void
    {
        $this->delivery_rate = $data['delivery_rate'] ?? null;
        $this->failure_rate = $data['failure_rate'] ?? null;
        $this->read_rate = $data['read_rate'] ?? null;
    }
}
