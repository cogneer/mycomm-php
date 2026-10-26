<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

final class ContactBulkItemError extends Model
{
    public readonly string $code;

    public readonly string $message;

    /**
     * @var array<string, mixed>
     */
    public readonly array $details;

    protected function hydrate(array $data): void
    {
        $this->code = $data['code'];
        $this->message = $data['message'];
        $this->details = $data['details'];
    }
}
