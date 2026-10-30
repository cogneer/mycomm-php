<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use DateTimeImmutable;
use MyComm\Enums;
use MyComm\Model;

/**
 * A line you made, in your user's account.
 */
final class PartnerLine extends Model
{
    public readonly string $id;

    /**
     * Your own reference for the line, unique among your live lines; a detached line frees it.
     */
    public readonly string $external_ref;

    public readonly string $name;

    public readonly string $account_id;

    /**
     * The channel types the user may connect for this line; `null` for every available type.
     *
     * @var list<string>|null
     */
    public readonly ?array $allowed_channel_types;

    /**
     * Whether the line can send.
     */
    public readonly Enums\LineState|string $state;

    public readonly DateTimeImmutable $created_at;

    /**
     * When the line was detached; `null` while it is live.
     */
    public readonly ?DateTimeImmutable $archived_at;

    protected function hydrate(array $data): void
    {
        $this->id = $data['id'];
        $this->external_ref = $data['external_ref'];
        $this->name = $data['name'];
        $this->account_id = $data['account_id'];
        $this->allowed_channel_types = $data['allowed_channel_types'] ?? null;
        $this->state = self::enum(Enums\LineState::class, $data['state']);
        $this->created_at = self::dateTime($data['created_at']);
        $this->archived_at = isset($data['archived_at']) ? self::dateTime($data['archived_at']) : null;
    }
}
