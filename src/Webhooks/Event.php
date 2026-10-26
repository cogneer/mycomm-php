<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Webhooks;

use DateTimeImmutable;
use MyComm\Model;
use MyComm\Models;

/**
 * A webhook event. `type` names it; `data` (on each event class) carries what happened.
 *
 * Delivery is at least once and unordered: de-duplicate on `id` and never move a status backwards.
 */
abstract class Event extends Model
{
    /**
     * The event id. The same for every endpoint and every retry or replay of this event; use it to de-duplicate.
     */
    public readonly string $id;

    public readonly string $type;

    /**
     * The `info.version` of the API document the payload shape follows. A change tells a receiver the contract moved.
     */
    public readonly string $api_version;

    public readonly DateTimeImmutable $created_at;

    public readonly string $account_id;

    /**
     * The managed account's `external_ref`; `null` for direct accounts.
     */
    public readonly ?string $account_external_ref;

    public readonly ?string $inbox_id;

    public readonly ?Models\WebhookChannelRef $channel;

    final protected function hydrate(array $data): void
    {
        $this->id = $data['id'];
        $this->type = $data['type'];
        $this->api_version = $data['api_version'];
        $this->created_at = self::dateTime($data['created_at']);
        $this->account_id = $data['account_id'];
        $this->account_external_ref = $data['account_external_ref'] ?? null;
        $this->inbox_id = $data['inbox_id'] ?? null;
        $this->channel = isset($data['channel']) ? Models\WebhookChannelRef::fromArray($data['channel']) : null;
        $this->hydrateData($data);
    }

    /**
     * @param  array<string, mixed>  $data  The whole event.
     */
    abstract protected function hydrateData(array $data): void;
}
