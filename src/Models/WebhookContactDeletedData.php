<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

final class WebhookContactDeletedData extends Model
{
    public readonly string $contact_id;

    /**
     * True for an erasure: the contact and its data are gone.
     */
    public readonly bool $erased;

    /**
     * The contact this one was merged into, else `null`.
     */
    public readonly ?string $merged_into;

    protected function hydrate(array $data): void
    {
        $this->contact_id = $data['contact_id'];
        $this->erased = $data['erased'];
        $this->merged_into = $data['merged_into'] ?? null;
    }
}
