<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use DateTimeImmutable;
use MyComm\Model;

/**
 * Payload of line.recovery_requested: who asked, as they gave it. The number is proved by a code, the name
 * is not.
 */
final class WebhookLineRecoveryRequestedData extends Model
{
    public readonly string $line_id;

    public readonly string $recovery_request_id;

    public readonly string $contact_name;

    public readonly string $contact_number;

    public readonly DateTimeImmutable $requested_at;

    public readonly DateTimeImmutable $waiting_until;

    protected function hydrate(array $data): void
    {
        $this->line_id = $data['line_id'];
        $this->recovery_request_id = $data['recovery_request_id'];
        $this->contact_name = $data['contact_name'];
        $this->contact_number = $data['contact_number'];
        $this->requested_at = self::dateTime($data['requested_at']);
        $this->waiting_until = self::dateTime($data['waiting_until']);
    }
}
