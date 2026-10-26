<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Webhooks\Events;

use MyComm\Models;
use MyComm\Webhooks\Event;

/**
 * `account.reactivated`: A suspended account is `active` again.
 */
final class AccountReactivated extends Event
{
    public const TYPE = 'account.reactivated';

    public readonly Models\WebhookAccountData $data;

    protected function hydrateData(array $data): void
    {
        $this->data = Models\WebhookAccountData::fromArray($data['data']);
    }
}
