<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Enums;
use MyComm\Model;

/**
 * Adds the contact to, or removes it from, a group of the account.
 */
final class AutomationGroupAction extends Model
{
    public readonly Enums\AutomationGroupActionType|string $type;

    public readonly string $group_id;

    protected function hydrate(array $data): void
    {
        $this->type = self::enum(Enums\AutomationGroupActionType::class, $data['type']);
        $this->group_id = $data['group_id'];
    }
}
