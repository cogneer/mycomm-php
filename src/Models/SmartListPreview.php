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

final class SmartListPreview extends Model
{
    public readonly int $count;

    /**
     * @var list<Contact>
     */
    public readonly array $sample;

    protected function hydrate(array $data): void
    {
        $this->count = $data['count'];
        $this->sample = array_map(static fn (array $item): Contact => Contact::fromArray($item), array_values($data['sample']));
    }
}
