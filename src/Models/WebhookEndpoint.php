<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use DateTimeImmutable;
use MyComm\Model;

final class WebhookEndpoint extends Model
{
    public readonly string $id;

    public readonly string $url;

    public readonly ?string $description;

    /**
     * Empty = every event except the opt-in ones.
     *
     * @var list<string>
     */
    public readonly array $events;

    /**
     * @var list<string>|null
     */
    public readonly ?array $inbox_ids;

    /**
     * @var list<string>|null
     */
    public readonly ?array $channel_types;

    public readonly bool $include_managed_accounts;

    public readonly bool $active;

    public readonly ?DateTimeImmutable $disabled_at;

    public readonly ?string $disabled_reason;

    public readonly int $consecutive_failures;

    public readonly ?DateTimeImmutable $failing_since;

    public readonly ?DateTimeImmutable $previous_secret_expires_at;

    public readonly DateTimeImmutable $created_at;

    public readonly DateTimeImmutable $updated_at;

    protected function hydrate(array $data): void
    {
        $this->id = $data['id'];
        $this->url = $data['url'];
        $this->description = $data['description'] ?? null;
        $this->events = $data['events'];
        $this->inbox_ids = $data['inbox_ids'] ?? null;
        $this->channel_types = $data['channel_types'] ?? null;
        $this->include_managed_accounts = $data['include_managed_accounts'];
        $this->active = $data['active'];
        $this->disabled_at = isset($data['disabled_at']) ? self::dateTime($data['disabled_at']) : null;
        $this->disabled_reason = $data['disabled_reason'] ?? null;
        $this->consecutive_failures = $data['consecutive_failures'];
        $this->failing_since = isset($data['failing_since']) ? self::dateTime($data['failing_since']) : null;
        $this->previous_secret_expires_at = isset($data['previous_secret_expires_at']) ? self::dateTime($data['previous_secret_expires_at']) : null;
        $this->created_at = self::dateTime($data['created_at']);
        $this->updated_at = self::dateTime($data['updated_at']);
    }
}
