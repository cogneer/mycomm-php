<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Webhooks\Events;

use MyComm\Models;
use MyComm\Webhooks\Event;

/**
 * `contact.opted_in`: A suppression was removed (any method); `suppression_id` is the removed suppression.
 */
final class ContactOptedIn extends Event
{
    public const TYPE = 'contact.opted_in';

    public readonly Models\WebhookConsentData $data;

    protected function hydrateData(array $data): void
    {
        $this->data = Models\WebhookConsentData::fromArray($data['data']);
    }
}
