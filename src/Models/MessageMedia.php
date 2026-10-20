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

final class MessageMedia extends Model
{
    public readonly string $id;

    public readonly string $mime;

    public readonly int $size_bytes;

    public readonly ?int $width;

    public readonly ?int $height;

    public readonly ?string $filename;

    protected function hydrate(array $data): void
    {
        $this->id = $data['id'];
        $this->mime = $data['mime'];
        $this->size_bytes = $data['size_bytes'];
        $this->width = $data['width'] ?? null;
        $this->height = $data['height'] ?? null;
        $this->filename = $data['filename'] ?? null;
    }
}
