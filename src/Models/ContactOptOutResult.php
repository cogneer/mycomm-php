<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

final class ContactOptOutResult extends Model
{
    /**
     * Identities suppressed by this call.
     */
    public readonly int $suppressed;

    /**
     * Identities that were already suppressed and were left unchanged.
     */
    public readonly int $already_suppressed;

    protected function hydrate(array $data): void
    {
        $this->suppressed = $data['suppressed'];
        $this->already_suppressed = $data['already_suppressed'];
    }
}
