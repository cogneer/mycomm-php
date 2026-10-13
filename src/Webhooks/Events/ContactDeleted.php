<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Webhooks\Events;

use MyComm\Models;
use MyComm\Webhooks\Event;

/**
 * `contact.deleted`: A contact was soft-deleted, erased (`erased: true`; nothing of it remains) or merged
 * into another contact (`merged_into`). The data carries ids only.
 */
final class ContactDeleted extends Event
{
    public const TYPE = 'contact.deleted';

    public readonly Models\WebhookContactDeletedData $data;

    protected function hydrateData(array $data): void
    {
        $this->data = Models\WebhookContactDeletedData::fromArray($data['data']);
    }
}
