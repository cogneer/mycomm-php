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
 * Who created the message (00 R9); `id` is `null` for `system`.
 */
final class MessageActor extends Model
{
    public readonly string $type;

    public readonly ?string $id;

    protected function hydrate(array $data): void
    {
        $this->type = $data['type'];
        $this->id = $data['id'] ?? null;
    }
}
