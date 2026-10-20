<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

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
use MyComm\Omit;
use MyComm\Page;
use MyComm\RequestOptions;

final class WebhookExportDataExport extends Model
{
    public readonly string $id;

    public readonly Enums\ExportType|string $type;

    public readonly Enums\ExportStatus|string $status;

    /**
     * @var array<string, mixed>
     */
    public readonly array $params;

    /**
     * Always `null` in the webhook; `GET /v1/exports/{id}` signs the download URL.
     */
    public readonly null $url;

    public readonly null $url_expires_at;

    /**
     * Completion plus 7 days; `null` until completed.
     */
    public readonly ?DateTimeImmutable $expires_at;

    public readonly DateTimeImmutable $created_at;

    public readonly ?DateTimeImmutable $completed_at;

    protected function hydrate(array $data): void
    {
        $this->id = $data['id'];
        $this->type = self::enum(Enums\ExportType::class, $data['type']);
        $this->status = self::enum(Enums\ExportStatus::class, $data['status']);
        $this->params = $data['params'];
        $this->url = null;
        $this->url_expires_at = null;
        $this->expires_at = isset($data['expires_at']) ? self::dateTime($data['expires_at']) : null;
        $this->created_at = self::dateTime($data['created_at']);
        $this->completed_at = isset($data['completed_at']) ? self::dateTime($data['completed_at']) : null;
    }
}
