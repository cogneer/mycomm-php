<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

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
