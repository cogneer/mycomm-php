<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

final class SuppressionCheck extends Model
{
    /**
     * The address as stored (E.164 for a phone number).
     */
    public readonly string $address;

    public readonly bool $suppressed;

    public readonly ?Suppression $suppression;

    protected function hydrate(array $data): void
    {
        $this->address = $data['address'];
        $this->suppressed = $data['suppressed'];
        $this->suppression = isset($data['suppression']) ? Suppression::fromArray($data['suppression']) : null;
    }
}
