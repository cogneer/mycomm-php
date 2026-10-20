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

final class PairingCode extends Model
{
    public readonly string $id;

    /**
     * 8 Crockford base32 characters; only in the creation response.
     */
    public readonly ?string $code;

    /**
     * `mc1|<base_url>|<code>`; render as-is as a QR code. Only in the creation response.
     */
    public readonly ?string $qr_payload;

    public readonly DateTimeImmutable $expires_at;

    public readonly string $download_url;

    public readonly Enums\PairingCodeStatus|string $status;

    public readonly ?string $device_id;

    public readonly ?string $device_name;

    public readonly ?PairingCodeLastError $last_error;

    public readonly DateTimeImmutable $created_at;

    protected function hydrate(array $data): void
    {
        $this->id = $data['id'];
        $this->code = $data['code'] ?? null;
        $this->qr_payload = $data['qr_payload'] ?? null;
        $this->expires_at = self::dateTime($data['expires_at']);
        $this->download_url = $data['download_url'];
        $this->status = self::enum(Enums\PairingCodeStatus::class, $data['status']);
        $this->device_id = $data['device_id'] ?? null;
        $this->device_name = $data['device_name'] ?? null;
        $this->last_error = isset($data['last_error']) ? PairingCodeLastError::fromArray($data['last_error']) : null;
        $this->created_at = self::dateTime($data['created_at']);
    }
}
