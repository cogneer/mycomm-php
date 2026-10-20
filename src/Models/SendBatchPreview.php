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

/**
 * The would-be message of a dry-run item. `id` and `status` are null; nothing is stored.
 */
final class SendBatchPreview extends Model
{
    public readonly null $id;

    public readonly null $status;

    public readonly ?string $to;

    public readonly ?string $body;

    public readonly ?string $encoding;

    public readonly ?int $segments;

    public readonly ?string $type;

    public readonly ?string $category;

    public readonly ?string $lane;

    public readonly ?string $hold_reason;

    public readonly ?string $reference_id;

    /**
     * @var array<string, mixed>|null
     */
    public readonly ?array $metadata;

    protected function hydrate(array $data): void
    {
        $this->id = null;
        $this->status = null;
        $this->to = $data['to'] ?? null;
        $this->body = $data['body'] ?? null;
        $this->encoding = $data['encoding'] ?? null;
        $this->segments = $data['segments'] ?? null;
        $this->type = $data['type'] ?? null;
        $this->category = $data['category'] ?? null;
        $this->lane = $data['lane'] ?? null;
        $this->hold_reason = $data['hold_reason'] ?? null;
        $this->reference_id = $data['reference_id'] ?? null;
        $this->metadata = $data['metadata'] ?? null;
    }
}
