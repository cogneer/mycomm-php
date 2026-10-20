<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Webhooks\Events;

use DateTimeImmutable;
use DateTimeInterface;
use Generator;
use MyComm\Enums;
use MyComm\Exceptions\ApiException;
use MyComm\Exceptions\ConnectionException;
use MyComm\FileUpload;
use MyComm\Http\Connection;
use MyComm\Http\Params;
use MyComm\Input;
use MyComm\Inputs;
use MyComm\Model;
use MyComm\Models;
use MyComm\Omit;
use MyComm\Page;
use MyComm\RequestOptions;
use MyComm\Webhooks\Event;

/**
 * `contact.updated`: Fields, identities, groups or the merge target of a contact changed; a bulk import
 * emits one event per changed contact. `data.changed` names what changed.
 */
final class ContactUpdated extends Event
{
    public const TYPE = 'contact.updated';

    public readonly Models\WebhookContactUpdatedData $data;

    protected function hydrateData(array $data): void
    {
        $this->data = Models\WebhookContactUpdatedData::fromArray($data['data']);
    }
}
