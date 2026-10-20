<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Webhooks\Events;

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
use MyComm\Models;
use MyComm\Omit;
use MyComm\Page;
use MyComm\RequestOptions;
use MyComm\Webhooks\Event;

/**
 * `channel.disconnected`: The number was disconnected (owner disconnect, SIM retirement, ops
 * force-release, account deletion or a stale release). `data.reason` is the `disconnect_reason`.
 */
final class ChannelDisconnected extends Event
{
    public const TYPE = 'channel.disconnected';

    public readonly Models\WebhookChannelDisconnectedData $data;

    protected function hydrateData(array $data): void
    {
        $this->data = Models\WebhookChannelDisconnectedData::fromArray($data['data']);
    }
}
