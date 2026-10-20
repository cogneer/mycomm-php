<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use DateTimeImmutable;
use DateTimeInterface;
use Generator;
use MyComm\Enums;
use MyComm\Exceptions\ApiException;
use MyComm\Exceptions\ConnectionException;
use MyComm\FileUpload;
use MyComm\Http\Connection;
use MyComm\Http\Params;
use MyComm\Input;
use MyComm\Inputs;
use MyComm\Model;
use MyComm\Omit;
use MyComm\Page;
use MyComm\RequestOptions;

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
