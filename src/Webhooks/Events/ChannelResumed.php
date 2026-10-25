<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Webhooks\Events;

use MyComm\Models;
use MyComm\Webhooks\Event;

/**
 * `channel.resumed`: A paused channel is `active` again.
 */
final class ChannelResumed extends Event
{
    public const TYPE = 'channel.resumed';

    public readonly Models\WebhookChannelData $data;

    protected function hydrateData(array $data): void
    {
        $this->data = Models\WebhookChannelData::fromArray($data['data']);
    }
}
