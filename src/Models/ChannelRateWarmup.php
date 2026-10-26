<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

final class ChannelRateWarmup extends Model
{
    public readonly bool $enabled;

    protected function hydrate(array $data): void
    {
        $this->enabled = $data['enabled'];
    }
}
