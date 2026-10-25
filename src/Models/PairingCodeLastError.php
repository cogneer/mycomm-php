<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use DateTimeImmutable;
use MyComm\Model;

final class PairingCodeLastError extends Model
{
    /**
     * For example `DEVICE_IN_USE` or `DECLINED_ON_DEVICE`.
     */
    public readonly string $code;

    public readonly DateTimeImmutable $at;

    protected function hydrate(array $data): void
    {
        $this->code = $data['code'];
        $this->at = self::dateTime($data['at']);
    }
}
