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

final class StatsDailyResponse extends Model
{
    public readonly string $timezone;

    public readonly string $from;

    public readonly string $to;

    public readonly Enums\StatsGroupBy|string $group_by;

    /**
     * @var list<DailyStatsRow>
     */
    public readonly array $data;

    public readonly ?string $next_cursor;

    protected function hydrate(array $data): void
    {
        $this->timezone = $data['timezone'];
        $this->from = $data['from'];
        $this->to = $data['to'];
        $this->group_by = self::enum(Enums\StatsGroupBy::class, $data['group_by']);
        $this->data = array_map(static fn (array $item): DailyStatsRow => DailyStatsRow::fromArray($item), array_values($data['data']));
        $this->next_cursor = $data['next_cursor'] ?? null;
    }
}
