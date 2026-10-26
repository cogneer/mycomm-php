<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use DateTimeImmutable;
use MyComm\Enums;
use MyComm\Model;

final class MessageEvent extends Model
{
    /**
     * Increasing event id.
     */
    public readonly int $id;

    public readonly Enums\MessageEventName|string $event;

    /**
     * `api`, `device`, `system`, `portal` and others; new values may appear.
     */
    public readonly string $source;

    /**
     * @var array<string, mixed>
     */
    public readonly array $data;

    public readonly DateTimeImmutable $created_at;

    protected function hydrate(array $data): void
    {
        $this->id = $data['id'];
        $this->event = self::enum(Enums\MessageEventName::class, $data['event']);
        $this->source = $data['source'];
        $this->data = $data['data'];
        $this->created_at = self::dateTime($data['created_at']);
    }
}
