<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Webhooks\Events;

use MyComm\Models;
use MyComm\Webhooks\Event;

/**
 * `device.revoked`: The device was unpaired or revoked. `data.reason` is the `revoked_reason`.
 */
final class DeviceRevoked extends Event
{
    public const TYPE = 'device.revoked';

    public readonly Models\WebhookDeviceRevokedData $data;

    protected function hydrateData(array $data): void
    {
        $this->data = Models\WebhookDeviceRevokedData::fromArray($data['data']);
    }
}
