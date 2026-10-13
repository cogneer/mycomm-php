<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

final class ContactBulkResponse extends Model
{
    public readonly ContactBulkResponseSummary $summary;

    /**
     * @var list<ContactBulkResult>
     */
    public readonly array $results;

    protected function hydrate(array $data): void
    {
        $this->summary = ContactBulkResponseSummary::fromArray($data['summary']);
        $this->results = array_map(static fn (array $item): ContactBulkResult => ContactBulkResult::fromArray($item), array_values($data['results']));
    }
}
