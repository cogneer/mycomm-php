<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Webhooks\Events;

use MyComm\Models;
use MyComm\Webhooks\Event;

/**
 * `line.failover`: Queued messages of a line moved from one sender to another because the first could not
 * send: it left the line, was disconnected, stayed unhealthy longer than the line's `failover_seconds`
 * allow for the lane (by default high 2 min, normal 15 min, bulk 60 min), reported a sender fault, or the
 * messages were waiting for any sender. One event per line, sender pair and sweep; `message_ids` lists at
 * most 100 of the `message_count` messages. Messages sent with a named `channel_id` or `from` never move.
 */
final class LineFailover extends Event
{
    public const TYPE = 'line.failover';

    public readonly Models\WebhookLineFailoverData $data;

    protected function hydrateData(array $data): void
    {
        $this->data = Models\WebhookLineFailoverData::fromArray($data['data']);
    }
}
