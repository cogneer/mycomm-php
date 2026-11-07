<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Webhooks\Events;

use MyComm\Models;
use MyComm\Webhooks\Event;

/**
 * `user.reactivated`: A suspended user is `active` again.
 */
final class UserReactivated extends Event
{
    public const TYPE = 'user.reactivated';

    public readonly Models\WebhookUserData $data;

    protected function hydrateData(array $data): void
    {
        $this->data = Models\WebhookUserData::fromArray($data['data']);
    }
}
