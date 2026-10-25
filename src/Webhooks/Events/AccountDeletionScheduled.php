<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Webhooks\Events;

use MyComm\Models;
use MyComm\Webhooks\Event;

/**
 * `account.deletion_scheduled`: The account is `pending_deletion`; `data.account.purge_at` is when it is
 * purged.
 */
final class AccountDeletionScheduled extends Event
{
    public const TYPE = 'account.deletion_scheduled';

    public readonly Models\WebhookAccountData $data;

    protected function hydrateData(array $data): void
    {
        $this->data = Models\WebhookAccountData::fromArray($data['data']);
    }
}
