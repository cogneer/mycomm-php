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
 * The warm-up of the number.
 */
final class ChannelWarmup extends Model
{
    public readonly bool $active;

    public readonly int $day;

    public readonly ?DateTimeImmutable $ends_at;

    public readonly ?int $today_cap;

    protected function hydrate(array $data): void
    {
        $this->active = $data['active'];
        $this->day = $data['day'];
        $this->ends_at = isset($data['ends_at']) ? self::dateTime($data['ends_at']) : null;
        $this->today_cap = $data['today_cap'] ?? null;
    }
}
