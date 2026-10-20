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

final class SuppressionCheck extends Model
{
    /**
     * The address as stored (E.164 for a phone number).
     */
    public readonly string $address;

    public readonly bool $suppressed;

    public readonly ?Suppression $suppression;

    protected function hydrate(array $data): void
    {
        $this->address = $data['address'];
        $this->suppressed = $data['suppressed'];
        $this->suppression = isset($data['suppression']) ? Suppression::fromArray($data['suppression']) : null;
    }
}
