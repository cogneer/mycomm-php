<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

final class SmartListsCountResponse extends Model
{
    public readonly int $count;

    protected function hydrate(array $data): void
    {
        $this->count = $data['count'];
    }
}
