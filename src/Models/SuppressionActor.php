<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

/**
 * Who added the suppression.
 */
final class SuppressionActor extends Model
{
    /**
     * `user`, `api_key`, `partner_key`, `system` and others; new values may appear.
     */
    public readonly string $type;

    public readonly ?string $id;

    protected function hydrate(array $data): void
    {
        $this->type = $data['type'];
        $this->id = $data['id'] ?? null;
    }
}
