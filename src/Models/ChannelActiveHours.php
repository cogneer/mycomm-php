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
 * The window in which the channel sends; `null` means any time.
 */
final class ChannelActiveHours extends Model
{
    public readonly ?string $start;

    public readonly ?string $end;

    public readonly ?string $timezone;

    protected function hydrate(array $data): void
    {
        $this->start = $data['start'] ?? null;
        $this->end = $data['end'] ?? null;
        $this->timezone = $data['timezone'] ?? null;
    }
}
