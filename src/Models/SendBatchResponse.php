<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

final class SendBatchResponse extends Model
{
    /**
     * Present and true for a dry run.
     */
    public readonly ?bool $dry_run;

    public readonly SendBatchResponseSummary $summary;

    /**
     * @var list<SendBatchResult>
     */
    public readonly array $results;

    protected function hydrate(array $data): void
    {
        $this->dry_run = $data['dry_run'] ?? null;
        $this->summary = SendBatchResponseSummary::fromArray($data['summary']);
        $this->results = array_map(static fn (array $item): SendBatchResult => SendBatchResult::fromArray($item), array_values($data['results']));
    }
}
