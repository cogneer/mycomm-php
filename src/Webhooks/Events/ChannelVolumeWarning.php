<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Webhooks\Events;

use MyComm\Models;
use MyComm\Webhooks\Event;

/**
 * `channel.volume_warning`: 80 % of the effective daily cap was used. Sending is not blocked; further
 * messages wait.
 */
final class ChannelVolumeWarning extends Event
{
    public const TYPE = 'channel.volume_warning';

    public readonly Models\WebhookChannelVolumeWarningData $data;

    protected function hydrateData(array $data): void
    {
        $this->data = Models\WebhookChannelVolumeWarningData::fromArray($data['data']);
    }
}
