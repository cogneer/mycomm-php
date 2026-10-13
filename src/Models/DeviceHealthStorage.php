<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

final class DeviceHealthStorage extends Model
{
    public readonly ?int $free_mb;

    public readonly ?bool $low;

    protected function hydrate(array $data): void
    {
        $this->free_mb = $data['free_mb'] ?? null;
        $this->low = $data['low'] ?? null;
    }
}
