<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Inputs;

use MyComm\Enums;
use MyComm\Http\Params;
use MyComm\Input;

/**
 * Marks the conversation read, or archives it.
 */
final class AutomationFlagAction extends Input
{
    public function __construct(
        public readonly Enums\AutomationFlagActionType|string $type,
    ) {}

    public function toArray(): array
    {
        return Params::body([
            'type' => $this->type,
        ]);
    }
}
