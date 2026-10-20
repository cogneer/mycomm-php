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

final class StatsSummaryRates extends Model
{
    public readonly ?float $delivery_rate;

    public readonly ?float $failure_rate;

    public readonly ?float $read_rate;

    protected function hydrate(array $data): void
    {
        $this->delivery_rate = $data['delivery_rate'] ?? null;
        $this->failure_rate = $data['failure_rate'] ?? null;
        $this->read_rate = $data['read_rate'] ?? null;
    }
}
