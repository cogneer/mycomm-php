<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Webhooks\Events;

use MyComm\Models;
use MyComm\Webhooks\Event;

/**
 * `contact.created`: A contact was created (API, portal, import, or inbound from a new address).
 * `data.contact` is the contact resource.
 */
final class ContactCreated extends Event
{
    public const TYPE = 'contact.created';

    public readonly Models\WebhookContactData $data;

    protected function hydrateData(array $data): void
    {
        $this->data = Models\WebhookContactData::fromArray($data['data']);
    }
}
