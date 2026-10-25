<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Enums;
use MyComm\Model;

/**
 * Marks the conversation read, or archives it.
 */
final class AutomationFlagAction extends Model
{
    public readonly Enums\AutomationFlagActionType|string $type;

    protected function hydrate(array $data): void
    {
        $this->type = self::enum(Enums\AutomationFlagActionType::class, $data['type']);
    }
}
