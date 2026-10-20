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
 * Outbound messages waiting on the channel.
 */
final class ChannelQueue extends Model
{
    /**
     * Queued and sendable now.
     */
    public readonly int $queued;

    /**
     * Queued with a `send_after` still ahead.
     */
    public readonly int $held;

    /**
     * Handed to the phone and not yet reported.
     */
    public readonly int $leased;

    protected function hydrate(array $data): void
    {
        $this->queued = $data['queued'];
        $this->held = $data['held'];
        $this->leased = $data['leased'];
    }
}
