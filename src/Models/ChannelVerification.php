<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use DateTimeImmutable;
use MyComm\Enums;
use MyComm\Http\Connection;
use MyComm\Model;

/**
 * The latest verification of the number.
 */
final class ChannelVerification extends Model
{
    public readonly string $id;

    public readonly Enums\ChannelVerificationStatus|string $status;

    /**
     * The latest progress step.
     */
    public readonly Enums\ChannelVerificationStep|string $step;

    public readonly Enums\ChannelVerificationMethod|string $method;

    public readonly int $attempts;

    /**
     * The on-phone confirmation request for this SIM connection, or `null` before one exists.
     */
    public readonly ?ChannelConfirmation $confirmation;

    public readonly Enums\ChannelVerificationFailureReason|string|null $failure_reason;

    public readonly ?DateTimeImmutable $expires_at;

    public readonly ?DateTimeImmutable $verified_at;

    protected function hydrate(array $data): void
    {
        $this->id = $data['id'];
        $this->status = self::enum(Enums\ChannelVerificationStatus::class, $data['status']);
        $this->step = self::enum(Enums\ChannelVerificationStep::class, $data['step']);
        $this->method = self::enum(Enums\ChannelVerificationMethod::class, $data['method']);
        $this->attempts = $data['attempts'];
        $this->confirmation = isset($data['confirmation']) ? ChannelConfirmation::fromArray($data['confirmation']) : null;
        $this->failure_reason = isset($data['failure_reason']) ? self::enum(Enums\ChannelVerificationFailureReason::class, $data['failure_reason']) : null;
        $this->expires_at = isset($data['expires_at']) ? self::dateTime($data['expires_at']) : null;
        $this->verified_at = isset($data['verified_at']) ? self::dateTime($data['verified_at']) : null;
    }
}
