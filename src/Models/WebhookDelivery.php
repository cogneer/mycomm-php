<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use DateTimeImmutable;
use MyComm\Enums;
use MyComm\Model;

final class WebhookDelivery extends Model
{
    public readonly string $id;

    public readonly string $endpoint_id;

    public readonly string $event_id;

    public readonly string $event_type;

    public readonly Enums\WebhookDeliveryStatus|string $status;

    public readonly int $attempts;

    public readonly ?DateTimeImmutable $next_attempt_at;

    public readonly ?DateTimeImmutable $last_attempt_at;

    public readonly ?DateTimeImmutable $delivered_at;

    public readonly ?int $last_status_code;

    public readonly ?string $last_error;

    public readonly ?string $last_response_excerpt;

    public readonly ?int $response_ms;

    /**
     * The exact signed request body; `null` once removed after 30 days.
     */
    public readonly ?string $payload;

    public readonly DateTimeImmutable $created_at;

    protected function hydrate(array $data): void
    {
        $this->id = $data['id'];
        $this->endpoint_id = $data['endpoint_id'];
        $this->event_id = $data['event_id'];
        $this->event_type = $data['event_type'];
        $this->status = self::enum(Enums\WebhookDeliveryStatus::class, $data['status']);
        $this->attempts = $data['attempts'];
        $this->next_attempt_at = isset($data['next_attempt_at']) ? self::dateTime($data['next_attempt_at']) : null;
        $this->last_attempt_at = isset($data['last_attempt_at']) ? self::dateTime($data['last_attempt_at']) : null;
        $this->delivered_at = isset($data['delivered_at']) ? self::dateTime($data['delivered_at']) : null;
        $this->last_status_code = $data['last_status_code'] ?? null;
        $this->last_error = $data['last_error'] ?? null;
        $this->last_response_excerpt = $data['last_response_excerpt'] ?? null;
        $this->response_ms = $data['response_ms'] ?? null;
        $this->payload = $data['payload'] ?? null;
        $this->created_at = self::dateTime($data['created_at']);
    }
}
