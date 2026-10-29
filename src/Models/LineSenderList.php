<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

final class LineSenderList extends Model
{
    /**
     * @var list<LineSender>
     */
    public readonly array $data;

    protected function hydrate(array $data): void
    {
        $this->data = array_map(static fn (array $item): LineSender => LineSender::fromArray($item), array_values($data['data']));
    }
}
