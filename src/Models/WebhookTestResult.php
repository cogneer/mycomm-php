<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

final class WebhookTestResult extends Model
{
    public readonly string $delivery_id;

    public readonly bool $success;

    public readonly ?int $status_code;

    public readonly ?int $response_ms;

    public readonly ?string $error;

    protected function hydrate(array $data): void
    {
        $this->delivery_id = $data['delivery_id'];
        $this->success = $data['success'];
        $this->status_code = $data['status_code'] ?? null;
        $this->response_ms = $data['response_ms'] ?? null;
        $this->error = $data['error'] ?? null;
    }
}
