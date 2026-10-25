<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Webhooks\Events;

use MyComm\Models;
use MyComm\Webhooks\Event;

/**
 * `contact.opted_out`: A suppression was created (any method). With scope `contact` one event is emitted
 * per suppressed identity.
 */
final class ContactOptedOut extends Event
{
    public const TYPE = 'contact.opted_out';

    public readonly Models\WebhookConsentData $data;

    protected function hydrateData(array $data): void
    {
        $this->data = Models\WebhookConsentData::fromArray($data['data']);
    }
}
