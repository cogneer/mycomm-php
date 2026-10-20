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

final class WebhookDeviceStateData extends Model
{
    public readonly Device $device;

    public readonly Enums\DeviceConnectionState|string $previous_state;

    protected function hydrate(array $data): void
    {
        $this->device = Device::fromArray($data['device']);
        $this->previous_state = self::enum(Enums\DeviceConnectionState::class, $data['previous_state']);
    }
}
