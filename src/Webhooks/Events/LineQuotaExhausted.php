<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Webhooks\Events;

use MyComm\Models;
use MyComm\Webhooks\Event;

/**
 * `line.quota_exhausted`: A sponsored partner line used all the messages its partner pays for this cycle,
 * for one channel type. Further messages of that type wait with `hold_reason` `sponsor_quota_exhausted`
 * until the next cycle, unless the sponsorship lets the user's own messages take over
 * (`allow_user_overage`). Sent once per cycle and type.
 */
final class LineQuotaExhausted extends Event
{
    public const TYPE = 'line.quota_exhausted';

    public readonly Models\WebhookLineQuotaData $data;

    protected function hydrateData(array $data): void
    {
        $this->data = Models\WebhookLineQuotaData::fromArray($data['data']);
    }
}
