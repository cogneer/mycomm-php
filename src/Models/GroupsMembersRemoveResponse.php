<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

final class GroupsMembersRemoveResponse extends Model
{
    public readonly int $removed;

    public readonly int $not_member;

    protected function hydrate(array $data): void
    {
        $this->removed = $data['removed'];
        $this->not_member = $data['not_member'];
    }
}
