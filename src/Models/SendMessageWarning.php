<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

final class SendMessageWarning extends Model
{
    public readonly string $code;

    public readonly ?string $path;

    public readonly string $message;

    protected function hydrate(array $data): void
    {
        $this->code = $data['code'];
        $this->path = $data['path'] ?? null;
        $this->message = $data['message'];
    }
}
