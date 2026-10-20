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

final class ContactBulkResult extends Model
{
    /**
     * Position of the item in `contacts`.
     */
    public readonly int $index;

    public readonly Enums\ContactBulkResultStatus|string $status;

    /**
     * The created or updated contact; null when rejected.
     */
    public readonly ?string $contact_id;

    public readonly ?ContactBulkItemError $error;

    protected function hydrate(array $data): void
    {
        $this->index = $data['index'];
        $this->status = self::enum(Enums\ContactBulkResultStatus::class, $data['status']);
        $this->contact_id = $data['contact_id'] ?? null;
        $this->error = isset($data['error']) ? ContactBulkItemError::fromArray($data['error']) : null;
    }
}
