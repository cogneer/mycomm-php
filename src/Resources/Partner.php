<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Resources;

use MyComm\Http\Connection;

final class Partner
{
    public readonly Partner\Lines $lines;

    public readonly Partner\Links $links;

    public readonly Partner\SponsorshipDefaults $sponsorshipDefaults;

    public readonly Partner\Usage $usage;

    public function __construct(private readonly Connection $connection)
    {
        $this->lines = new Partner\Lines($connection);
        $this->links = new Partner\Links($connection);
        $this->sponsorshipDefaults = new Partner\SponsorshipDefaults($connection);
        $this->usage = new Partner\Usage($connection);
    }
}
