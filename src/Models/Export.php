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

final class Export extends Model
{
    public readonly string $id;

    public readonly Enums\ExportType|string $type;

    public readonly Enums\ExportStatus|string $status;

    /**
     * @var array<string, mixed>
     */
    public readonly array $params;

    /**
     * Signed download URL, valid 15 minutes; present only when `completed` and only on `GET /v1/exports/{id}` (`null` in list rows). Treat it as a secret.
     */
    public readonly ?string $url;

    public readonly ?DateTimeImmutable $url_expires_at;

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
        $this->url = $data['url'] ?? null;
        $this->url_expires_at = isset($data['url_expires_at']) ? self::dateTime($data['url_expires_at']) : null;
        $this->expires_at = isset($data['expires_at']) ? self::dateTime($data['expires_at']) : null;
        $this->created_at = self::dateTime($data['created_at']);
        $this->completed_at = isset($data['completed_at']) ? self::dateTime($data['completed_at']) : null;
    }
}
