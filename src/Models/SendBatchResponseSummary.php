<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

final class SendBatchResponseSummary extends Model
{
    public readonly int $accepted;

    public readonly int $rejected;

    protected function hydrate(array $data): void
    {
        $this->accepted = $data['accepted'];
        $this->rejected = $data['rejected'];
    }
}
