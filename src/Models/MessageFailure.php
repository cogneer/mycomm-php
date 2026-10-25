<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

/**
 * Failure code and its wording; see the status and failures guide.
 */
final class MessageFailure extends Model
{
    public readonly string $code;

    public readonly string $message;

    protected function hydrate(array $data): void
    {
        $this->code = $data['code'];
        $this->message = $data['message'];
    }
}
