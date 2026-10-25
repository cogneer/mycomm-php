<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

/**
 * `business_hours`, an optional `signature` and notification preferences. Missing keys read as `null`.
 */
final class InboxSettings extends Model
{
    /**
     * @var array<string, mixed>|null
     */
    public readonly ?array $business_hours;

    public readonly ?string $signature;

    protected function hydrate(array $data): void
    {
        $this->business_hours = $data['business_hours'] ?? null;
        $this->signature = $data['signature'] ?? null;
    }
}
