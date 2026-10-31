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
     * @param  ?bool  $mirror_inbound  Copy every new inbound on this channel that lands on another line to this line (see `LineSender`). When left out, an entry already on the list keeps its value and a new entry starts with `false`.
     */
    public function __construct(
        public readonly string $channel_id,
        public readonly Enums\LineSenderRole|string|null $role = null,
        public readonly ?bool $enabled = null,
        public readonly ?bool $mirror_inbound = null,
    ) {}

    public function toArray(): array
    {
        return Params::body([
            'channel_id' => $this->channel_id,
            'role' => $this->role ?? Omit::Value,
            'enabled' => $this->enabled ?? Omit::Value,
            'mirror_inbound' => $this->mirror_inbound ?? Omit::Value,
        ]);
    }
}
