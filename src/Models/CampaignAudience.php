<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

/**
 * `addresses` is left out of the items of `GET /campaigns`; `GET /campaigns/{id}` returns it.
 */
final class CampaignAudience extends Model
{
    /**
     * @var list<string>
     */
    public readonly array $group_ids;

    /**
     * @var list<string>
     */
    public readonly array $smart_list_ids;

    /**
     * The campaign CSV import whose rows are the audience (created from the contacts CSV import given on create or update).
     */
    public readonly ?string $import_id;

    /**
     * @var list<CampaignAddress>|null
     */
    public readonly ?array $addresses;

    protected function hydrate(array $data): void
    {
        $this->group_ids = $data['group_ids'];
        $this->smart_list_ids = $data['smart_list_ids'];
        $this->import_id = $data['import_id'] ?? null;
        $this->addresses = isset($data['addresses']) ? array_map(static fn (array $item): CampaignAddress => CampaignAddress::fromArray($item), array_values($data['addresses'])) : null;
    }
}
