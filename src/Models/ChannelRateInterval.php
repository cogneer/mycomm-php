<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

final class ChannelRateInterval extends Model
{
    public readonly int $min;

    public readonly int $max;

    protected function hydrate(array $data): void
    {
        $this->min = $data['min'];
        $this->max = $data['max'];
    }
}
