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

final class StatsSummaryDevices extends Model
{
    public readonly int $online;

    public readonly int $degraded;

    public readonly int $offline;

    protected function hydrate(array $data): void
    {
        $this->online = $data['online'];
        $this->degraded = $data['degraded'];
        $this->offline = $data['offline'];
    }
}
