<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use DateTimeImmutable;
use MyComm\Enums;
use MyComm\Model;

final class HostedLink extends Model
{
    public readonly string $id;

    public readonly Enums\HostedLinkPurpose|string $purpose;

    public readonly string $line_id;

    public readonly Enums\HostedLinkStatus|string $status;

    public readonly DateTimeImmutable $expires_at;

    public readonly ?string $return_url;

    /**
     * @var list<string>|null
     */
    public readonly ?array $allowed_origins;

    public readonly ?string $locale;

    public readonly ?string $replaces_device_id;

    /**
     * Set when the link paired a phone.
     */
    public readonly ?string $device_id;

    /**
     * Set when the link connected a channel.
     */
    public readonly ?string $channel_id;

    public readonly ?DateTimeImmutable $completed_at;

    public readonly ?DateTimeImmutable $revoked_at;

    public readonly DateTimeImmutable $created_at;

    protected function hydrate(array $data): void
    {
        $this->id = $data['id'];
        $this->purpose = self::enum(Enums\HostedLinkPurpose::class, $data['purpose']);
        $this->line_id = $data['line_id'];
        $this->status = self::enum(Enums\HostedLinkStatus::class, $data['status']);
        $this->expires_at = self::dateTime($data['expires_at']);
        $this->return_url = $data['return_url'] ?? null;
        $this->allowed_origins = $data['allowed_origins'] ?? null;
        $this->locale = $data['locale'] ?? null;
        $this->replaces_device_id = $data['replaces_device_id'] ?? null;
        $this->device_id = $data['device_id'] ?? null;
        $this->channel_id = $data['channel_id'] ?? null;
        $this->completed_at = isset($data['completed_at']) ? self::dateTime($data['completed_at']) : null;
        $this->revoked_at = isset($data['revoked_at']) ? self::dateTime($data['revoked_at']) : null;
        $this->created_at = self::dateTime($data['created_at']);
    }
}
