<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Resources;

use MyComm\Http\Connection;

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
