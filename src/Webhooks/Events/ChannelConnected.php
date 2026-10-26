<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Webhooks\Events;

use MyComm\Models;
use MyComm\Webhooks\Event;

/**
 * `channel.connected`: A number finished verification and is `active`. `data.channel` is the channel
 * resource.
 */
final class ChannelConnected extends Event
{
    public const TYPE = 'channel.connected';

    public readonly Models\WebhookChannelData $data;

    protected function hydrateData(array $data): void
    {
        $this->data = Models\WebhookChannelData::fromArray($data['data']);
    }
}
