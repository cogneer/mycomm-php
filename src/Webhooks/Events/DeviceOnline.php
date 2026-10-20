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
 * `device.online`: The device's `connection_state` became `online`; `data.previous_state` is the state
 * before.
 */
final class DeviceOnline extends Event
{
    public const TYPE = 'device.online';

    public readonly Models\WebhookDeviceStateData $data;

    protected function hydrateData(array $data): void
    {
        $this->data = Models\WebhookDeviceStateData::fromArray($data['data']);
    }
}
