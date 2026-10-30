<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Webhooks\Events;

use MyComm\Models;
use MyComm\Webhooks\Event;

/**
 * `line.needs_attention`: A connected partner line needs its user: `needs_attention_reasons` says why.
 * Sent when the line enters the state and again when a new reason is added (a reason that goes away is not
 * sent). The same as `GET /partner/v1/lines/{id}/status` at that moment.
 */
final class LineNeedsAttention extends Event
{
    public const TYPE = 'line.needs_attention';

    public readonly Models\WebhookLineStatusChangedData $data;

    protected function hydrateData(array $data): void
    {
        $this->data = Models\WebhookLineStatusChangedData::fromArray($data['data']);
    }
}
