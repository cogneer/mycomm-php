<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Enums;
use MyComm\Model;

final class WebhookDeviceRevokedData extends Model
{
    public readonly Device $device;

    /**
     * The device's `revoked_reason`.
     */
    public readonly Enums\DeviceRevokedReason|string $reason;

    protected function hydrate(array $data): void
    {
        $this->device = Device::fromArray($data['device']);
        $this->reason = self::enum(Enums\DeviceRevokedReason::class, $data['reason']);
    }
}
