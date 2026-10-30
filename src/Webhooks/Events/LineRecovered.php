<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Webhooks\Events;

use MyComm\Models;
use MyComm\Webhooks\Event;

/**
 * `line.recovered`: A partner line that needed attention is connected again. Sent once per recovery.
 */
final class LineRecovered extends Event
{
    public const TYPE = 'line.recovered';

    public readonly Models\WebhookLineStatusChangedData $data;

    protected function hydrateData(array $data): void
    {
        $this->data = Models\WebhookLineStatusChangedData::fromArray($data['data']);
    }
}
