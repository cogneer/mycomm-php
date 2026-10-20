<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Resources;

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
use MyComm\Models;
use MyComm\Omit;
use MyComm\Page;
use MyComm\RequestOptions;

final class Partner
{
    public readonly Partner\Usage $usage;

    public readonly Partner\Accounts $accounts;

    public readonly Partner\AccountKeys $accountKeys;

    public function __construct(private readonly Connection $connection)
    {
        $this->usage = new Partner\Usage($connection);
        $this->accounts = new Partner\Accounts($connection);
        $this->accountKeys = new Partner\AccountKeys($connection);
    }
}
