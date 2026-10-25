<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Webhooks\Events;

use MyComm\Models;
use MyComm\Webhooks\Event;

/**
 * `export.completed`: An export file is ready. `data.export` is the export resource with `url: null`:
 * fetch the signed download URL with `GET /v1/exports/{id}`. A failed export emits nothing; poll the
 * export instead.
 */
final class ExportCompleted extends Event
{
    public const TYPE = 'export.completed';

    public readonly Models\WebhookExportData $data;

    protected function hydrateData(array $data): void
    {
        $this->data = Models\WebhookExportData::fromArray($data['data']);
    }
}
