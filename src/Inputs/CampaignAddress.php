<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Inputs;

use MyComm\Http\Params;
use MyComm\Input;
use MyComm\Omit;

final class CampaignAddress extends Input
{
    /**
     * @param  array<string, mixed>|null  $variables
     */
    public function __construct(
        public readonly string $address,
        public readonly ?array $variables = null,
    ) {}

    public function toArray(): array
    {
        return Params::body([
            'address' => $this->address,
            'variables' => Params::object($this->variables ?? Omit::Value),
        ]);
    }
}
