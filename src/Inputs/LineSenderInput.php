<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Inputs;

use MyComm\Enums;
use MyComm\Http\Params;
use MyComm\Input;
use MyComm\Omit;

final class LineSenderInput extends Input
{
    /**
     * @param  ?bool  $enabled  Default `true`.
     */
    public function __construct(
        public readonly string $channel_id,
        public readonly Enums\LineSenderRole|string|null $role = null,
        public readonly ?bool $enabled = null,
    ) {}

    public function toArray(): array
    {
        return Params::body([
            'channel_id' => $this->channel_id,
            'role' => $this->role ?? Omit::Value,
            'enabled' => $this->enabled ?? Omit::Value,
        ]);
    }
}
