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

final class Media extends Model
{
    public readonly string $id;

    public readonly string $mime;

    public readonly int $size_bytes;

    public readonly ?int $width;

    public readonly ?int $height;

    public readonly ?int $duration_ms;

    public readonly string $sha256;

    public readonly ?string $original_name;

    /**
     * `message` for an upload; `template` or `campaign` once referenced from one.
     */
    public readonly string $purpose;

    /**
     * The first message that links this media; `null` while unattached.
     */
    public readonly ?string $message_id;

    /**
     * Presigned download URL, valid 15 minutes. Treat it as a secret.
     */
    public readonly string $url;

    public readonly DateTimeImmutable $url_expires_at;

    public readonly DateTimeImmutable $created_at;

    protected function hydrate(array $data): void
    {
        $this->id = $data['id'];
        $this->mime = $data['mime'];
        $this->size_bytes = $data['size_bytes'];
        $this->width = $data['width'] ?? null;
        $this->height = $data['height'] ?? null;
        $this->duration_ms = $data['duration_ms'] ?? null;
        $this->sha256 = $data['sha256'];
        $this->original_name = $data['original_name'] ?? null;
        $this->purpose = $data['purpose'];
        $this->message_id = $data['message_id'] ?? null;
        $this->url = $data['url'];
        $this->url_expires_at = self::dateTime($data['url_expires_at']);
        $this->created_at = self::dateTime($data['created_at']);
    }
}
