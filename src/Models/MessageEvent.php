<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use DateTimeImmutable;
use MyComm\Model;

final class MessageEvent extends Model
{
    /**
     * Increasing event id.
     */
    public readonly int $id;

    /**
     * Event name of 01 section 4.5 (`accepted`, `leased`, `sent`, `delivered`, `failed`, ...); new values may appear.
     */
    public readonly string $event;

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
        $this->event = $data['event'];
        $this->source = $data['source'];
        $this->data = $data['data'];
        $this->created_at = self::dateTime($data['created_at']);
    }
}
