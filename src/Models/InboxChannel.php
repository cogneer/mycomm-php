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
 * Denormalised summary of the inbox's channel.
 */
final class InboxChannel extends Model
{
    public readonly string $id;

    public readonly Enums\ChannelType|string $type;

    public readonly string $name;

    public readonly ?string $address;

    public readonly ?string $display_address;

    public readonly string $status;

    protected function hydrate(array $data): void
    {
        $this->id = $data['id'];
        $this->type = self::enum(Enums\ChannelType::class, $data['type']);
        $this->name = $data['name'];
        $this->address = $data['address'] ?? null;
        $this->display_address = $data['display_address'] ?? null;
        $this->status = $data['status'];
    }
}
