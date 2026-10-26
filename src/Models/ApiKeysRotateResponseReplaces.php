<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use DateTimeImmutable;
use MyComm\Model;

/**
 * The old key and when it stops working.
 */
final class ApiKeysRotateResponseReplaces extends Model
{
    public readonly string $id;

    public readonly ?DateTimeImmutable $expires_at;

    public readonly ?DateTimeImmutable $revoked_at;

    protected function hydrate(array $data): void
    {
        $this->id = $data['id'];
        $this->expires_at = isset($data['expires_at']) ? self::dateTime($data['expires_at']) : null;
        $this->revoked_at = isset($data['revoked_at']) ? self::dateTime($data['revoked_at']) : null;
    }
}
