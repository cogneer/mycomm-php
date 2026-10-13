<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use DateTimeImmutable;
use MyComm\Enums;
use MyComm\Model;

final class Import extends Model
{
    public readonly string $id;

    public readonly Enums\ImportType|string $type;

    public readonly Enums\ImportStatus|string $status;

    public readonly ?string $channel_id;

    /**
     * @var array<string, mixed>
     */
    public readonly array $options;

    public readonly ImportTotals $totals;

    /**
     * Always `null` for CSV imports; the on-phone confirmation request of a `phone_history` import.
     *
     * @var array<string, mixed>|null
     */
    public readonly ?array $confirmation;

    /**
     * Why the import failed or was cancelled.
     */
    public readonly ?string $error;

    /**
     * Signed URL of the error report CSV (`row, column, value, error`), valid 15 minutes. Treat it as a secret.
     */
    public readonly ?string $error_report_url;

    public readonly ?DateTimeImmutable $error_report_url_expires_at;

    public readonly ?DateTimeImmutable $started_at;

    public readonly ?DateTimeImmutable $completed_at;

    public readonly DateTimeImmutable $created_at;

    protected function hydrate(array $data): void
    {
        $this->id = $data['id'];
        $this->type = self::enum(Enums\ImportType::class, $data['type']);
        $this->status = self::enum(Enums\ImportStatus::class, $data['status']);
        $this->channel_id = $data['channel_id'] ?? null;
        $this->options = $data['options'];
        $this->totals = ImportTotals::fromArray($data['totals']);
        $this->confirmation = $data['confirmation'] ?? null;
        $this->error = $data['error'] ?? null;
        $this->error_report_url = $data['error_report_url'] ?? null;
        $this->error_report_url_expires_at = isset($data['error_report_url_expires_at']) ? self::dateTime($data['error_report_url_expires_at']) : null;
        $this->started_at = isset($data['started_at']) ? self::dateTime($data['started_at']) : null;
        $this->completed_at = isset($data['completed_at']) ? self::dateTime($data['completed_at']) : null;
        $this->created_at = self::dateTime($data['created_at']);
    }
}
