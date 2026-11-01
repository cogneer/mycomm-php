<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Webhooks\Events;

use MyComm\Models;
use MyComm\Webhooks\Event;

/**
 * `line.recovery_requested`: Someone who says they lost their number asked to recover the account of this
 * line; sent once per partner, on its oldest live line of the account. Nothing has changed on the account.
 * If you know this person, answer with `POST /partner/v1/lines/{id}/vouch`. It is never required and never
 * enough alone: MyComm staff decide, no earlier than `waiting_until`.
 */
final class LineRecoveryRequested extends Event
{
    public const TYPE = 'line.recovery_requested';

    public readonly Models\WebhookLineRecoveryRequestedData $data;

    protected function hydrateData(array $data): void
    {
        $this->data = Models\WebhookLineRecoveryRequestedData::fromArray($data['data']);
    }
}
