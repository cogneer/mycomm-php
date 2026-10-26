<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use DateTimeImmutable;
use MyComm\Model;

final class RotatedWebhookSecret extends Model
{
    public readonly string $secret;

    public readonly DateTimeImmutable $previous_secret_expires_at;

    protected function hydrate(array $data): void
    {
        $this->secret = $data['secret'];
        $this->previous_secret_expires_at = self::dateTime($data['previous_secret_expires_at']);
    }
}
