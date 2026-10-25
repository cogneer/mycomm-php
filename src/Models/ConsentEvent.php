<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use DateTimeImmutable;
use MyComm\Enums;
use MyComm\Model;

/**
 * One opt-out or opt-in of the consent audit trail.
 */
final class ConsentEvent extends Model
{
    /**
     * Increasing event id.
     */
    public readonly int $id;

    public readonly string $identity_type;

    public readonly string $address;

    public readonly Enums\ConsentEventType|string $event;

    /**
     * `keyword`, `manual`, `api`, `import` or `campaign_form`; new values may appear.
     */
    public readonly string $method;

    /**
     * The keyword of a keyword opt-out or opt-in.
     */
    public readonly ?string $keyword;

    /**
     * The inbound message that carried the keyword.
     */
    public readonly ?string $message_id;

    /**
     * Who made the change.
     */
    public readonly ConsentEventActor $actor;

    public readonly ?string $note;

    public readonly DateTimeImmutable $created_at;

    protected function hydrate(array $data): void
    {
        $this->id = $data['id'];
        $this->identity_type = $data['identity_type'];
        $this->address = $data['address'];
        $this->event = self::enum(Enums\ConsentEventType::class, $data['event']);
        $this->method = $data['method'];
        $this->keyword = $data['keyword'] ?? null;
        $this->message_id = $data['message_id'] ?? null;
        $this->actor = ConsentEventActor::fromArray($data['actor']);
        $this->note = $data['note'] ?? null;
        $this->created_at = self::dateTime($data['created_at']);
    }
}
