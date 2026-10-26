<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

final class ImportTotals extends Model
{
    public readonly int $rows;

    public readonly int $created;

    public readonly int $updated;

    public readonly int $skipped;

    public readonly int $errors;

    protected function hydrate(array $data): void
    {
        $this->rows = $data['rows'];
        $this->created = $data['created'];
        $this->updated = $data['updated'];
        $this->skipped = $data['skipped'];
        $this->errors = $data['errors'];
    }
}
