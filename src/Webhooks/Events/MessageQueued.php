<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Webhooks\Events;

use MyComm\Models;
use MyComm\Webhooks\Event;

/**
 * `message.queued`: A message was accepted, or a manual retry re-queued it. Opt-in: list it in the
 * endpoint's `events`.
 */
final class MessageQueued extends Event
{
    public const TYPE = 'message.queued';

    public readonly Models\WebhookMessageData $data;

    protected function hydrateData(array $data): void
    {
        $this->data = Models\WebhookMessageData::fromArray($data['data']);
    }
}
