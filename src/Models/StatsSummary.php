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

final class StatsSummary extends Model
{
    public readonly string $timezone;

    public readonly string $from;

    public readonly string $to;

    public readonly StatsSummaryTotals $totals;

    public readonly StatsSummaryRates $rates;

    /**
     * Failure code to count.
     *
     * @var array<string, int>
     */
    public readonly array $failures_by_code;

    public readonly StatsSummaryQueueNow $queue_now;

    public readonly StatsSummaryChannels $channels;

    public readonly StatsSummaryDevices $devices;

    protected function hydrate(array $data): void
    {
        $this->timezone = $data['timezone'];
        $this->from = $data['from'];
        $this->to = $data['to'];
        $this->totals = StatsSummaryTotals::fromArray($data['totals']);
        $this->rates = StatsSummaryRates::fromArray($data['rates']);
        $this->failures_by_code = $data['failures_by_code'];
        $this->queue_now = StatsSummaryQueueNow::fromArray($data['queue_now']);
        $this->channels = StatsSummaryChannels::fromArray($data['channels']);
        $this->devices = StatsSummaryDevices::fromArray($data['devices']);
    }
}
