<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use DateTimeImmutable;
use MyComm\Model;

final class CampaignCompliance extends Model
{
    public readonly ?DateTimeImmutable $consent_asserted_at;

    public readonly ?CampaignActor $consent_asserted_by;

    public readonly bool $service_message;

    protected function hydrate(array $data): void
    {
        $this->consent_asserted_at = isset($data['consent_asserted_at']) ? self::dateTime($data['consent_asserted_at']) : null;
        $this->consent_asserted_by = isset($data['consent_asserted_by']) ? CampaignActor::fromArray($data['consent_asserted_by']) : null;
        $this->service_message = $data['service_message'];
    }
}
