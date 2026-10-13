<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

final class ChannelMediaLimits extends Model
{
    public readonly int $max_bytes;

    /**
     * @var list<string>
     */
    public readonly array $mimes;

    /**
     * The size a larger file is re-encoded to (the phone's learned MMS limit).
     */
    public readonly ?int $target_bytes;

    protected function hydrate(array $data): void
    {
        $this->max_bytes = $data['max_bytes'];
        $this->mimes = $data['mimes'];
        $this->target_bytes = $data['target_bytes'] ?? null;
    }
}
