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
 * The window in which the `bulk` lane may send; `tz` `null` means the channel country's time zone.
 */
final class ChannelBulkHours extends Model
{
    public readonly ?string $start;

    public readonly ?string $end;

    public readonly ?string $tz;

    protected function hydrate(array $data): void
    {
        $this->start = $data['start'] ?? null;
        $this->end = $data['end'] ?? null;
        $this->tz = $data['tz'] ?? null;
    }
}
