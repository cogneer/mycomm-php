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
 * `import.completed`: An import finished (any type; a user cancel emits nothing). `data.import` is the
 * import resource with its totals. `error_report_url` is signed for 15 minutes from when the event was
 * rendered, so a later retry or replay may hold an expired URL; `GET /v1/imports/{id}` signs a fresh one.
 */
final class ImportCompleted extends Event
{
    public const TYPE = 'import.completed';

    public readonly Models\WebhookImportData $data;

    protected function hydrateData(array $data): void
    {
        $this->data = Models\WebhookImportData::fromArray($data['data']);
    }
}
