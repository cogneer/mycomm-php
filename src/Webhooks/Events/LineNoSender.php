<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Webhooks\Events;

use MyComm\Models;
use MyComm\Webhooks\Event;

/**
 * `line.no_sender`: No sender of the line can send: messages wait with `hold_reason` `no_sender_available`
 * and move to the first sender that can again. Sent once when the line loses its last healthy sender, not
 * again until one came back.
 */
final class LineNoSender extends Event
{
    public const TYPE = 'line.no_sender';

    public readonly Models\WebhookLineNoSenderData $data;

    protected function hydrateData(array $data): void
    {
        $this->data = Models\WebhookLineNoSenderData::fromArray($data['data']);
    }
}
