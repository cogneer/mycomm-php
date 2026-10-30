<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

final class SponsorshipDefaults extends Model
{
    public readonly ?SponsorshipTerms $default_sponsorship;

    protected function hydrate(array $data): void
    {
        $this->default_sponsorship = isset($data['default_sponsorship']) ? SponsorshipTerms::fromArray($data['default_sponsorship']) : null;
    }
}
