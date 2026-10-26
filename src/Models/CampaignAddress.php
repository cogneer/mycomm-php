<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

final class CampaignAddress extends Model
{
    public readonly string $address;

    /**
     * @var array<string, string|int|float|bool>|null
     */
    public readonly ?array $variables;

    protected function hydrate(array $data): void
    {
        $this->address = $data['address'];
        $this->variables = $data['variables'] ?? null;
    }
}
