<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Webhooks\Events;

use MyComm\Models;
use MyComm\Webhooks\Event;

/**
 * `user.deletion_scheduled`: The user is `pending_deletion`; `data.user.purge_at` is when it is purged.
 */
final class UserDeletionScheduled extends Event
{
    public const TYPE = 'user.deletion_scheduled';

    public readonly Models\WebhookUserData $data;

    protected function hydrateData(array $data): void
    {
        $this->data = Models\WebhookUserData::fromArray($data['data']);
    }
}
