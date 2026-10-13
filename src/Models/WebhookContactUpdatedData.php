<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

final class WebhookContactUpdatedData extends Model
{
    public readonly Contact $contact;

    /**
     * What changed, by contact resource name: a field (`first_name`, `email`, …), `custom_fields.<key>`, `identities` or `groups`. May be empty.
     *
     * @var list<string>
     */
    public readonly array $changed;

    protected function hydrate(array $data): void
    {
        $this->contact = Contact::fromArray($data['contact']);
        $this->changed = $data['changed'];
    }
}
