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
