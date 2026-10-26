<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

/**
 * Recipients that lack a variable without fallback; `first` lists at most 20.
 */
final class CampaignEstimateMissingVariables extends Model
{
    public readonly ?int $count;

    /**
     * @var list<array<string, mixed>>|null
     */
    public readonly ?array $first;

    protected function hydrate(array $data): void
    {
        $this->count = $data['count'] ?? null;
        $this->first = $data['first'] ?? null;
    }
}
