<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Webhooks\Events;

use MyComm\Models;
use MyComm\Webhooks\Event;

/**
 * `channel.paused`: The channel was paused. `data.reason` is the `pause_reason`; `dominant_code` and
 * `streak` are set only for `auto:failure_streak`.
 */
final class ChannelPaused extends Event
{
    public const TYPE = 'channel.paused';

    public readonly Models\WebhookChannelPausedData $data;

    protected function hydrateData(array $data): void
    {
        $this->data = Models\WebhookChannelPausedData::fromArray($data['data']);
    }
}
