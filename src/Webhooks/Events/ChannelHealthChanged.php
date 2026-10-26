<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Webhooks\Events;

use MyComm\Models;
use MyComm\Webhooks\Event;

/**
 * `channel.health_changed`: The SIM safety band of the channel changed. `data.health` is the `health`
 * block of the channel resource.
 */
final class ChannelHealthChanged extends Event
{
    public const TYPE = 'channel.health_changed';

    public readonly Models\WebhookChannelHealthChangedData $data;

    protected function hydrateData(array $data): void
    {
        $this->data = Models\WebhookChannelHealthChangedData::fromArray($data['data']);
    }
}
