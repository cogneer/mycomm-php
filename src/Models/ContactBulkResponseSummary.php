<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

final class ContactBulkResponseSummary extends Model
{
    public readonly int $created;

    public readonly int $updated;

    public readonly int $rejected;

    protected function hydrate(array $data): void
    {
        $this->created = $data['created'];
        $this->updated = $data['updated'];
        $this->rejected = $data['rejected'];
    }
}
