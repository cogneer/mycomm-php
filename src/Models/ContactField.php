<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use DateTimeImmutable;
use MyComm\Enums;
use MyComm\Model;

final class ContactField extends Model
{
    public readonly string $id;

    public readonly string $key;

    public readonly string $label;

    public readonly Enums\ContactFieldType|string $type;

    /**
     * Allowed values of a `select` field; `null` for other types.
     *
     * @var list<string>|null
     */
    public readonly ?array $options;

    public readonly int $position;

    public readonly DateTimeImmutable $created_at;

    protected function hydrate(array $data): void
    {
        $this->id = $data['id'];
        $this->key = $data['key'];
        $this->label = $data['label'];
        $this->type = self::enum(Enums\ContactFieldType::class, $data['type']);
        $this->options = $data['options'] ?? null;
        $this->position = $data['position'];
        $this->created_at = self::dateTime($data['created_at']);
    }
}
