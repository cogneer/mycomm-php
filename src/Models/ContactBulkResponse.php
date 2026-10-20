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
