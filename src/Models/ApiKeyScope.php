<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

final class ApiKeyScope extends Model
{
    /**
     * @var list<string>|null
     */
    public readonly ?array $inbox_ids;

    /**
     * @var list<string>|null
     */
    public readonly ?array $channel_types;

    protected function hydrate(array $data): void
    {
        $this->inbox_ids = $data['inbox_ids'] ?? null;
        $this->channel_types = $data['channel_types'] ?? null;
    }
}
