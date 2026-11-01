<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use DateTimeImmutable;
use MyComm\Model;

final class RecoveryVouch extends Model
{
    public readonly string $recovery_request_id;

    public readonly DateTimeImmutable $vouched_at;

    protected function hydrate(array $data): void
    {
        $this->recovery_request_id = $data['recovery_request_id'];
        $this->vouched_at = self::dateTime($data['vouched_at']);
    }
}
