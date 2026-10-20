<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use DateTimeImmutable;
use DateTimeInterface;
use Generator;
use MyComm\Enums;
use MyComm\Exceptions\ApiException;
use MyComm\Exceptions\ConnectionException;
use MyComm\FileUpload;
use MyComm\Http\Connection;
use MyComm\Http\Params;
use MyComm\Input;
use MyComm\Inputs;
use MyComm\Model;
use MyComm\Omit;
use MyComm\Page;
use MyComm\RequestOptions;

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
