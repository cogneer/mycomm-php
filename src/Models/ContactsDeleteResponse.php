<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

final class ContactsDeleteResponse extends Model
{
    public readonly string $status;

    protected function hydrate(array $data): void
    {
        $this->status = $data['status'];
    }
}
