<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

/**
 * The contact identity on the other side of the message.
 */
final class MessageIdentity extends Model
{
    public readonly string $id;

    /**
     * `phone`, `sender_id`, `whatsapp` or `telegram`; new values may appear.
     */
    public readonly string $type;

    public readonly string $address;

    public readonly string $display;

    protected function hydrate(array $data): void
    {
        $this->id = $data['id'];
        $this->type = $data['type'];
        $this->address = $data['address'];
        $this->display = $data['display'];
    }
}
