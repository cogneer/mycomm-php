<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Inputs;

use MyComm\Http\Params;
use MyComm\Input;
use MyComm\Omit;

/**
 * Sends a reply, either a literal `body` (at most 1,600 characters and 10 SMS parts) or a live
 * `template_id`. Not both.
 */
final class AutomationReplyAction extends Input
{
    public function __construct(
        public readonly mixed $type,
        public readonly ?string $body = null,
        public readonly ?string $template_id = null,
    ) {}

    public function toArray(): array
    {
        return Params::body([
            'type' => $this->type,
            'body' => $this->body ?? Omit::Value,
            'template_id' => $this->template_id ?? Omit::Value,
        ]);
    }
}
