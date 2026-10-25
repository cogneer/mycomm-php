<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Inputs;

use MyComm\Http\Params;
use MyComm\Input;

/**
 * Tags the conversation with a slug.
 */
final class AutomationTagAction extends Input
{
    public function __construct(
        public readonly mixed $type,
        public readonly string $tag,
    ) {}

    public function toArray(): array
    {
        return Params::body([
            'type' => $this->type,
            'tag' => $this->tag,
        ]);
    }
}
