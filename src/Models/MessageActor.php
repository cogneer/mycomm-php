<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Enums;
use MyComm\Model;

/**
 * Who created the message (00 R9); `id` is `null` for `system`.
 */
final class MessageActor extends Model
{
    public readonly Enums\ActorType|string $type;

    public readonly ?string $id;

    protected function hydrate(array $data): void
    {
        $this->type = self::enum(Enums\ActorType::class, $data['type']);
        $this->id = $data['id'] ?? null;
    }
}
