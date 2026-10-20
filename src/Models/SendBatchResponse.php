<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use DateTimeImmutable;
use DateTimeInterface;
use Generator;
use MyComm\Enums;
use MyComm\Exceptions\ApiException;
use MyComm\Exceptions\ConnectionException;
use MyComm\FileUpload;
use MyComm\Http\Connection;
use MyComm\Http\Params;
use MyComm\Input;
use MyComm\Inputs;
use MyComm\Model;
use MyComm\Omit;
use MyComm\Page;
use MyComm\RequestOptions;

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
