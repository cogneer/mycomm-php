<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Webhooks\Events;

use MyComm\Models;
use MyComm\Webhooks\Event;

/**
 * `line.sender_changed`: The sender list of a line changed: senders were set or reordered, a newly
 * verified number joined the Direct line, or a disconnected number left every line. `data.senders` is the
 * whole list after the change, in position order (0 first).
 */
final class LineSenderChanged extends Event
{
    public const TYPE = 'line.sender_changed';

    public readonly Models\WebhookLineSenderChangedData $data;

    protected function hydrateData(array $data): void
    {
        $this->data = Models\WebhookLineSenderChangedData::fromArray($data['data']);
    }
}
