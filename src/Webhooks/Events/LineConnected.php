<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Webhooks\Events;

use MyComm\Models;
use MyComm\Webhooks\Event;

/**
 * `line.connected`: A partner line left `not_connected`: the user connected a phone and its first number
 * was verified. Sent once per line, whatever the line's state then is: `state` says it, and when it is
 * `needs_attention` a `line.needs_attention` follows. Later recoveries send `line.recovered`. Partner
 * lines only (`partner_lines` endpoints and the account's own endpoints).
 */
final class LineConnected extends Event
{
    public const TYPE = 'line.connected';

    public readonly Models\WebhookLineStatusChangedData $data;

    protected function hydrateData(array $data): void
    {
        $this->data = Models\WebhookLineStatusChangedData::fromArray($data['data']);
    }
}
