<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

final class PartnerLineStatusSponsorshipByType extends Model
{
    public readonly int $allowance;

    public readonly int $used;

    protected function hydrate(array $data): void
    {
        $this->allowance = $data['allowance'];
        $this->used = $data['used'];
    }
}
