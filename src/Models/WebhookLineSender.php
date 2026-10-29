<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Enums;
use MyComm\Model;

/**
 * One entry of a line's sender list.
 */
final class WebhookLineSender extends Model
{
    public readonly string $channel_id;

    /**
     * `primary` is the line's main number; `backup` numbers take over in list order; `pool` numbers share the sending.
     */
    public readonly Enums\LineSenderRole|string $role;

    /**
     * Place in the list, 0 first.
     */
    public readonly int $position;

    /**
     * A disabled entry stays on the list but is never used to send.
     */
    public readonly bool $enabled;

    protected function hydrate(array $data): void
    {
        $this->channel_id = $data['channel_id'];
        $this->role = self::enum(Enums\LineSenderRole::class, $data['role']);
        $this->position = $data['position'];
        $this->enabled = $data['enabled'];
    }
}
