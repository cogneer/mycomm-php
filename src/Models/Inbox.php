<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use DateTimeImmutable;
use MyComm\Enums;
use MyComm\Model;

final class Inbox extends Model
{
    public readonly string $id;

    public readonly string $channel_id;

    public readonly Enums\InboxKind|string $kind;

    public readonly string $name;

    public readonly InboxChannel $channel;

    public readonly InboxSettings $settings;

    public readonly ?DateTimeImmutable $archived_at;

    public readonly int $open_conversations;

    /**
     * Open conversations with unread messages.
     */
    public readonly int $unread_conversations;

    public readonly DateTimeImmutable $created_at;

    public readonly DateTimeImmutable $updated_at;

    protected function hydrate(array $data): void
    {
        $this->id = $data['id'];
        $this->channel_id = $data['channel_id'];
        $this->kind = self::enum(Enums\InboxKind::class, $data['kind']);
        $this->name = $data['name'];
        $this->channel = InboxChannel::fromArray($data['channel']);
        $this->settings = InboxSettings::fromArray($data['settings']);
        $this->archived_at = isset($data['archived_at']) ? self::dateTime($data['archived_at']) : null;
        $this->open_conversations = $data['open_conversations'];
        $this->unread_conversations = $data['unread_conversations'];
        $this->created_at = self::dateTime($data['created_at']);
        $this->updated_at = self::dateTime($data['updated_at']);
    }
}
