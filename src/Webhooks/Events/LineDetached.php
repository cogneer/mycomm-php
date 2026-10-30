<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Webhooks\Events;

use MyComm\Models;
use MyComm\Webhooks\Event;

/**
 * `line.detached`: A partner line was detached and archived, by the partner (`DELETE
 * /partner/v1/lines/{id}`), by the account's owner, or by MyComm. The account keeps its numbers and
 * history; the line sends no more. Sent once.
 */
final class LineDetached extends Event
{
    public const TYPE = 'line.detached';

    public readonly Models\WebhookLineDetachedData $data;

    protected function hydrateData(array $data): void
    {
        $this->data = Models\WebhookLineDetachedData::fromArray($data['data']);
    }
}
