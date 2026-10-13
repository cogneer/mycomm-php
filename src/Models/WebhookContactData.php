<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

final class WebhookContactData extends Model
{
    public readonly Contact $contact;

    protected function hydrate(array $data): void
    {
        $this->contact = Contact::fromArray($data['contact']);
    }
}
