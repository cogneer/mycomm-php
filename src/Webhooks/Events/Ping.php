<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Webhooks\Events;

use MyComm\Models;
use MyComm\Webhooks\Event;

/**
 * `ping`: Sent by the endpoint test action only; not subscribable.
 */
final class Ping extends Event
{
    public const TYPE = 'ping';

    public readonly Models\WebhookPingData $data;

    protected function hydrateData(array $data): void
    {
        $this->data = Models\WebhookPingData::fromArray($data['data']);
    }
}
