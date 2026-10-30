<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

final class PartnerLineStatusChannel extends Model
{
    public readonly int $total;

    /**
     * Senders that can send now.
     */
    public readonly int $healthy;

    protected function hydrate(array $data): void
    {
        $this->total = $data['total'];
        $this->healthy = $data['healthy'];
    }
}
