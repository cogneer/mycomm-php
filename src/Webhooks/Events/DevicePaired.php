<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Webhooks\Events;

use MyComm\Models;
use MyComm\Webhooks\Event;

/**
 * `device.paired`: Pairing completed, after the confirmation on the phone. `data.device` is the device
 * resource.
 */
final class DevicePaired extends Event
{
    public const TYPE = 'device.paired';

    public readonly Models\WebhookDevicePairedData $data;

    protected function hydrateData(array $data): void
    {
        $this->data = Models\WebhookDevicePairedData::fromArray($data['data']);
    }
}
