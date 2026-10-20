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

final class ContactIdentity extends Model
{
    public readonly string $id;

    public readonly Enums\ContactIdentityType|string $type;

    public readonly string $address;

    public readonly ?string $display;

    /**
     * @var array<string, mixed>
     */
    public readonly array $meta;

    public readonly ?string $country_iso;

    public readonly bool $is_primary;

    public readonly bool $suppressed;

    public readonly ?DateTimeImmutable $blocked_at;

    public readonly DateTimeImmutable $created_at;

    protected function hydrate(array $data): void
    {
        $this->id = $data['id'];
        $this->type = self::enum(Enums\ContactIdentityType::class, $data['type']);
        $this->address = $data['address'];
        $this->display = $data['display'] ?? null;
        $this->meta = $data['meta'];
        $this->country_iso = $data['country_iso'] ?? null;
        $this->is_primary = $data['is_primary'];
        $this->suppressed = $data['suppressed'];
        $this->blocked_at = isset($data['blocked_at']) ? self::dateTime($data['blocked_at']) : null;
        $this->created_at = self::dateTime($data['created_at']);
    }
}
