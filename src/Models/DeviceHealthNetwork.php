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

final class DeviceHealthNetwork extends Model
{
    public readonly Enums\DeviceNetworkType|string|null $type;

    public readonly ?bool $validated;

    public readonly ?bool $airplane_mode;

    protected function hydrate(array $data): void
    {
        $this->type = isset($data['type']) ? self::enum(Enums\DeviceNetworkType::class, $data['type']) : null;
        $this->validated = $data['validated'] ?? null;
        $this->airplane_mode = $data['airplane_mode'] ?? null;
    }
}
