<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use DateTimeImmutable;
use MyComm\Model;

final class CampaignReportTimeline extends Model
{
    public readonly DateTimeImmutable $hour;

    public readonly int $sent;

    public readonly int $delivered;

    public readonly int $failed;

    protected function hydrate(array $data): void
    {
        $this->hour = self::dateTime($data['hour']);
        $this->sent = $data['sent'];
        $this->delivered = $data['delivered'];
        $this->failed = $data['failed'];
    }
}
