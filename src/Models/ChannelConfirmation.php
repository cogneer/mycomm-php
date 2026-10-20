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

final class ChannelConfirmation extends Model
{
    public readonly string $id;

    public readonly Enums\ChannelConfirmationStatus|string $status;

    public readonly ?DateTimeImmutable $expires_at;

    public readonly ?DateTimeImmutable $decided_at;

    protected function hydrate(array $data): void
    {
        $this->id = $data['id'];
        $this->status = self::enum(Enums\ChannelConfirmationStatus::class, $data['status']);
        $this->expires_at = isset($data['expires_at']) ? self::dateTime($data['expires_at']) : null;
        $this->decided_at = isset($data['decided_at']) ? self::dateTime($data['decided_at']) : null;
    }
}
