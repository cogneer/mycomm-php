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
 * Sent segments in rolling windows.
 */
final class ChannelUsage extends Model
{
    public readonly int $segments_15m;

    public readonly int $segments_1h;

    public readonly int $segments_24h;

    protected function hydrate(array $data): void
    {
        $this->segments_15m = $data['segments_15m'];
        $this->segments_1h = $data['segments_1h'];
        $this->segments_24h = $data['segments_24h'];
    }
}
