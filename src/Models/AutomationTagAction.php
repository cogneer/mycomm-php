<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

/**
 * Tags the conversation with a slug.
 */
final class AutomationTagAction extends Model
{
    public readonly string $type;

    public readonly string $tag;

    protected function hydrate(array $data): void
    {
        $this->type = $data['type'];
        $this->tag = $data['tag'];
    }
}
