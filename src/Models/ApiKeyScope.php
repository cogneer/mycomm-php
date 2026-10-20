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

final class ApiKeyScope extends Model
{
    /**
     * @var list<string>|null
     */
    public readonly ?array $inbox_ids;

    /**
     * @var list<string>|null
     */
    public readonly ?array $channel_types;

    protected function hydrate(array $data): void
    {
        $this->inbox_ids = $data['inbox_ids'] ?? null;
        $this->channel_types = $data['channel_types'] ?? null;
    }
}
