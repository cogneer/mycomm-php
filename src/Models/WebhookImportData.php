<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

final class WebhookImportData extends Model
{
    public readonly Import $import;

    protected function hydrate(array $data): void
    {
        $this->import = Import::fromArray($data['import']);
    }
}
