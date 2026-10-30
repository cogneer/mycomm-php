<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Webhooks\Events;

use MyComm\Models;
use MyComm\Webhooks\Event;

/**
 * `line.quota_low`: A sponsored partner line used 80% of the messages its partner pays for this cycle, for
 * one channel type. Sent once per cycle and type (when one send takes the line past 80% and to 100%, both
 * this and `line.quota_exhausted` are sent). Partner lines only.
 */
final class LineQuotaLow extends Event
{
    public const TYPE = 'line.quota_low';

    public readonly Models\WebhookLineQuotaData $data;

    protected function hydrateData(array $data): void
    {
        $this->data = Models\WebhookLineQuotaData::fromArray($data['data']);
    }
}
