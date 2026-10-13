<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Enums;
use MyComm\Model;

final class WebhookDeviceStateData extends Model
{
    public readonly Device $device;

    public readonly Enums\DeviceConnectionState|string $previous_state;

    protected function hydrate(array $data): void
    {
        $this->device = Device::fromArray($data['device']);
        $this->previous_state = self::enum(Enums\DeviceConnectionState::class, $data['previous_state']);
    }
}
