<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Webhooks\Events;

use MyComm\Models;
use MyComm\Webhooks\Event;

/**
 * `message.received`: An inbound message was stored. `data` adds `contact_created` and `mirrored`;
 * `conversation.created` is true when this message created the conversation. A line that mirrors the
 * channel gets a copy marked `mirrored`. An endpoint for all lines receives both events, so filter on
 * `mirrored`.
 */
final class MessageReceived extends Event
{
    public const TYPE = 'message.received';

    public readonly Models\WebhookMessageReceivedData $data;

    protected function hydrateData(array $data): void
    {
        $this->data = Models\WebhookMessageReceivedData::fromArray($data['data']);
    }
}
