<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Enums;
use MyComm\Model;

final class DeviceHealthPower extends Model
{
    public readonly ?int $battery_pct;

    public readonly ?bool $charging;

    public readonly Enums\DevicePowerMode|string|null $power_mode;

    protected function hydrate(array $data): void
    {
        $this->battery_pct = $data['battery_pct'] ?? null;
        $this->charging = $data['charging'] ?? null;
        $this->power_mode = isset($data['power_mode']) ? self::enum(Enums\DevicePowerMode::class, $data['power_mode']) : null;
    }
}
