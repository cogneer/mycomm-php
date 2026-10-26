<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

final class GroupsMembersAddResponse extends Model
{
    public readonly int $added;

    public readonly int $already_member;

    /**
     * @var list<string>
     */
    public readonly array $not_found;

    protected function hydrate(array $data): void
    {
        $this->added = $data['added'];
        $this->already_member = $data['already_member'];
        $this->not_found = $data['not_found'];
    }
}
