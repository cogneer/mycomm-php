<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

final class WebhookExportData extends Model
{
    public readonly WebhookExportDataExport $export;

    protected function hydrate(array $data): void
    {
        $this->export = WebhookExportDataExport::fromArray($data['export']);
    }
}
