<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

final class StatsSummaryChannels extends Model
{
    public readonly int $active;

    public readonly int $paused;

    public readonly int $pending_verification;

    public readonly int $disconnected;

    protected function hydrate(array $data): void
    {
        $this->active = $data['active'];
        $this->paused = $data['paused'];
        $this->pending_verification = $data['pending_verification'];
        $this->disconnected = $data['disconnected'];
    }
}
