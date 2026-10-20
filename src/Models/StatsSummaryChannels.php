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

final class StatsSummaryChannels extends Model
{
    public readonly int $active;

    public readonly int $paused;

    public readonly int $pending_verification;

    public readonly int $disconnected;

    protected function hydrate(array $data): void
    {
        $this->active = $data['active'];
        $this->paused = $data['paused'];
        $this->pending_verification = $data['pending_verification'];
        $this->disconnected = $data['disconnected'];
    }
}
