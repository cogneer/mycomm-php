<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Model;

final class WebhookUserData extends Model
{
    public readonly WebhookUser $user;

    public readonly WebhookActor $actor;

    /**
     * The suspension reason, or `null`.
     */
    public readonly ?string $reason;

    protected function hydrate(array $data): void
    {
        $this->user = WebhookUser::fromArray($data['user']);
        $this->actor = WebhookActor::fromArray($data['actor']);
        $this->reason = $data['reason'] ?? null;
    }
}
