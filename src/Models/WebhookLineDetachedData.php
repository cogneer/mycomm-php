<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Enums;
use MyComm\Model;

/**
 * Payload of line.detached.
 */
final class WebhookLineDetachedData extends Model
{
    public readonly string $line_id;

    /**
     * The partner's own reference for the line; free again for a new line.
     */
    public readonly ?string $external_ref;

    /**
     * `partner` with the partner key, `owner` by a person of the account, `system` by MyComm.
     */
    public readonly Enums\LineDetachedBy|string $by;

    protected function hydrate(array $data): void
    {
        $this->line_id = $data['line_id'];
        $this->external_ref = $data['external_ref'] ?? null;
        $this->by = self::enum(Enums\LineDetachedBy::class, $data['by']);
    }
}
