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

final class WebhookChannelDisconnectedData extends Model
{
    public readonly Channel $channel;

    /**
     * The channel's `disconnect_reason`.
     */
    public readonly Enums\ChannelDisconnectReason|string $reason;

    protected function hydrate(array $data): void
    {
        $this->channel = Channel::fromArray($data['channel']);
        $this->reason = self::enum(Enums\ChannelDisconnectReason::class, $data['reason']);
    }
}
