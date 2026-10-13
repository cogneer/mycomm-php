<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Webhooks\Events;

use MyComm\Models;
use MyComm\Webhooks\Event;

/**
 * `account.suspended`: The account was suspended by the partner, the owner or ops. An ops-initiated
 * suspension carries the actor `admin` with a `null` id.
 */
final class AccountSuspended extends Event
{
    public const TYPE = 'account.suspended';

    public readonly Models\WebhookAccountData $data;

    protected function hydrateData(array $data): void
    {
        $this->data = Models\WebhookAccountData::fromArray($data['data']);
    }
}
