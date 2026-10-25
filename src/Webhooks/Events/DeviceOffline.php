<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Webhooks\Events;

use MyComm\Models;
use MyComm\Webhooks\Event;

/**
 * `device.offline`: The device has been offline longer than the account's offline threshold;
 * `data.previous_state` is the state it was in before it went offline.
 */
final class DeviceOffline extends Event
{
    public const TYPE = 'device.offline';

    public readonly Models\WebhookDeviceStateData $data;

    protected function hydrateData(array $data): void
    {
        $this->data = Models\WebhookDeviceStateData::fromArray($data['data']);
    }
}
