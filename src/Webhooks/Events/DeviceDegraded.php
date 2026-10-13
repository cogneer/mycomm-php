<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Webhooks\Events;

use MyComm\Models;
use MyComm\Webhooks\Event;

/**
 * `device.degraded`: The device is reachable but something needs attention;
 * `data.device.connection_reasons` lists what.
 */
final class DeviceDegraded extends Event
{
    public const TYPE = 'device.degraded';

    public readonly Models\WebhookDeviceStateData $data;

    protected function hydrateData(array $data): void
    {
        $this->data = Models\WebhookDeviceStateData::fromArray($data['data']);
    }
}
