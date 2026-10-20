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

final class ContactBulkResponseSummary extends Model
{
    public readonly int $created;

    public readonly int $updated;

    public readonly int $rejected;

    protected function hydrate(array $data): void
    {
        $this->created = $data['created'];
        $this->updated = $data['updated'];
        $this->rejected = $data['rejected'];
    }
}
