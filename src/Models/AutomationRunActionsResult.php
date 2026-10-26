<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

final class AutomationRunActionsResult extends Model
{
    public readonly string $guard;

    /**
     * @var list<AutomationActionResult>
     */
    public readonly array $actions;

    protected function hydrate(array $data): void
    {
        $this->guard = $data['guard'];
        $this->actions = array_map(static fn (array $item): AutomationActionResult => AutomationActionResult::fromArray($item), array_values($data['actions']));
    }
}
