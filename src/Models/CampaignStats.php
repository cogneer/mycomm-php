<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use DateTimeImmutable;
use MyComm\Model;

/**
 * `campaigns.stats`; `skipped` counts rows by skip reason.
 */
final class CampaignStats extends Model
{
    public readonly int $total;

    public readonly ?int $excluded;

    public readonly ?int $duplicates;

    public readonly ?DateTimeImmutable $expanded_at;

    /**
     * @var array<string, int>
     */
    public readonly array $skipped;

    protected function hydrate(array $data): void
    {
        $this->total = $data['total'];
        $this->excluded = $data['excluded'] ?? null;
        $this->duplicates = $data['duplicates'] ?? null;
        $this->expanded_at = isset($data['expanded_at']) ? self::dateTime($data['expanded_at']) : null;
        $this->skipped = $data['skipped'];
    }
}
