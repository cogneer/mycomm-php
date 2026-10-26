<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

final class CampaignExclude extends Model
{
    /**
     * @var list<string>
     */
    public readonly array $group_ids;

    protected function hydrate(array $data): void
    {
        $this->group_ids = $data['group_ids'];
    }
}
