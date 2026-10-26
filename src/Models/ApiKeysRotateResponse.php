<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use DateTimeImmutable;
use MyComm\Model;

final class ApiKeysRotateResponse extends Model
{
    public readonly string $id;

    public readonly string $kind;

    public readonly string $name;

    /**
     * The first 12 characters of the token, for recognising a key.
     */
    public readonly string $prefix;

    public readonly ApiKeyScope $scope;

    /**
     * @var list<string>
     */
    public readonly array $abilities;

    /**
     * @var list<string>|null
     */
    public readonly ?array $allowed_ips;

    public readonly ?DateTimeImmutable $last_used_at;

    public readonly ?string $last_used_ip;

    public readonly ?DateTimeImmutable $expires_at;

    public readonly ?DateTimeImmutable $revoked_at;

    public readonly ?ApiKeyActor $actor;

    public readonly DateTimeImmutable $created_at;

    /**
     * The plaintext key; returned only here.
     */
    public readonly string $token;

    /**
     * The old key and when it stops working.
     */
    public readonly ApiKeysRotateResponseReplaces $replaces;

    protected function hydrate(array $data): void
    {
        $this->id = $data['id'];
        $this->kind = $data['kind'];
        $this->name = $data['name'];
        $this->prefix = $data['prefix'];
        $this->scope = ApiKeyScope::fromArray($data['scope']);
        $this->abilities = $data['abilities'];
        $this->allowed_ips = $data['allowed_ips'] ?? null;
        $this->last_used_at = isset($data['last_used_at']) ? self::dateTime($data['last_used_at']) : null;
        $this->last_used_ip = $data['last_used_ip'] ?? null;
        $this->expires_at = isset($data['expires_at']) ? self::dateTime($data['expires_at']) : null;
        $this->revoked_at = isset($data['revoked_at']) ? self::dateTime($data['revoked_at']) : null;
        $this->actor = isset($data['actor']) ? ApiKeyActor::fromArray($data['actor']) : null;
        $this->created_at = self::dateTime($data['created_at']);
        $this->token = $data['token'];
        $this->replaces = ApiKeysRotateResponseReplaces::fromArray($data['replaces']);
    }
}
