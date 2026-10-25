<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

/**
 * A non-fatal notice returned next to a successful result (for example in `warnings[]`).
 */
final class Warning extends Model
{
    public readonly string $code;

    public readonly string $message;

    /**
     * Field path the warning is about, when there is one.
     */
    public readonly ?string $field;

    protected function hydrate(array $data): void
    {
        $this->code = $data['code'];
        $this->message = $data['message'];
        $this->field = $data['field'] ?? null;
    }
}
