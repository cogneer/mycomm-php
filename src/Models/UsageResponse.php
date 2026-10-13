<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

final class UsageResponse extends Model
{
    public readonly string $from;

    public readonly string $to;

    public readonly UsageTotals $totals;

    /**
     * @var list<UsageRow>
     */
    public readonly array $data;

    public readonly ?string $next_cursor;

    protected function hydrate(array $data): void
    {
        $this->from = $data['from'];
        $this->to = $data['to'];
        $this->totals = UsageTotals::fromArray($data['totals']);
        $this->data = array_map(static fn (array $item): UsageRow => UsageRow::fromArray($item), array_values($data['data']));
        $this->next_cursor = $data['next_cursor'] ?? null;
    }
}
