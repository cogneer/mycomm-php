<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Inputs;

use MyComm\Enums;
use MyComm\Http\Params;
use MyComm\Input;

/**
 * Adds the contact to, or removes it from, a group of the account.
 */
final class AutomationGroupAction extends Input
{
    public function __construct(
        public readonly Enums\AutomationGroupActionType|string $type,
        public readonly string $group_id,
    ) {}

    public function toArray(): array
    {
        return Params::body([
            'type' => $this->type,
            'group_id' => $this->group_id,
        ]);
    }
}
