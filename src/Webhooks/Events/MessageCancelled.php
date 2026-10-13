<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Webhooks\Events;

use MyComm\Models;
use MyComm\Webhooks\Event;

/**
 * `message.cancelled`: The message moved to `cancelled`; `data.reason` says why.
 */
final class MessageCancelled extends Event
{
    public const TYPE = 'message.cancelled';

    public readonly Models\WebhookMessageCancelledData $data;

    protected function hydrateData(array $data): void
    {
        $this->data = Models\WebhookMessageCancelledData::fromArray($data['data']);
    }
}
