<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

/**
 * How long a message of each lane waits on a sender that cannot send before it moves to another sender of
 * the line, in seconds (30 to 7200). Defaults: high 120, normal 900, bulk 3600.
 */
final class LineFailoverSeconds extends Model
{
    public readonly int $high;

    public readonly int $normal;

    public readonly int $bulk;

    protected function hydrate(array $data): void
    {
        $this->high = $data['high'];
        $this->normal = $data['normal'];
        $this->bulk = $data['bulk'];
    }
}
