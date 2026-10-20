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

final class ChannelRateCaps extends Model
{
    public readonly int $per_min;

    public readonly int $per_15m;

    public readonly int $per_hour;

    public readonly int $per_day;

    /**
     * The country preset's value for `android_sim` (150 for PK) unless a custom policy sets a lower one.
     */
    public readonly ?int $new_recipients_per_day;

    protected function hydrate(array $data): void
    {
        $this->per_min = $data['per_min'];
        $this->per_15m = $data['per_15m'];
        $this->per_hour = $data['per_hour'];
        $this->per_day = $data['per_day'];
        $this->new_recipients_per_day = $data['new_recipients_per_day'] ?? null;
    }
}
