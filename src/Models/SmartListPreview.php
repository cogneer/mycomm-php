<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

final class SmartListPreview extends Model
{
    public readonly int $count;

    /**
     * @var list<Contact>
     */
    public readonly array $sample;

    protected function hydrate(array $data): void
    {
        $this->count = $data['count'];
        $this->sample = array_map(static fn (array $item): Contact => Contact::fromArray($item), array_values($data['sample']));
    }
}
