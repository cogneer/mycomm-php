<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use DateTimeImmutable;
use MyComm\Enums;
use MyComm\Model;

/**
 * The automation rule resource. Optional values are `null`, never omitted.
 */
final class AutomationRule extends Model
{
    public readonly string $id;

    public readonly string $inbox_id;

    public readonly Enums\AutomationRuleType|string $type;

    public readonly string $name;

    /**
     * 1 to n among the keyword rules; 0 for the away message.
     */
    public readonly int $position;

    public readonly bool $active;

    public readonly AutomationRuleMatch $match;

    /**
     * @var list<AutomationReplyAction|AutomationGroupAction|AutomationTagAction|AutomationFlagAction|array<string, mixed>>
     */
    public readonly array $actions;

    /**
     * Always `null`; the away message follows the inbox's business hours.
     *
     * @var array<string, mixed>|null
     */
    public readonly ?array $schedule;

    public readonly int $cooldown_minutes;

    /**
     * Who created the rule; `null` for a rule saved before the actor was kept.
     */
    public readonly ?AutomationRuleActor $actor;

    public readonly DateTimeImmutable $created_at;

    public readonly DateTimeImmutable $updated_at;

    protected function hydrate(array $data): void
    {
        $this->id = $data['id'];
        $this->inbox_id = $data['inbox_id'];
        $this->type = self::enum(Enums\AutomationRuleType::class, $data['type']);
        $this->name = $data['name'];
        $this->position = $data['position'];
        $this->active = $data['active'];
        $this->match = AutomationRuleMatch::fromArray($data['match']);
        $this->actions = array_map(static fn (array $item): AutomationReplyAction|AutomationGroupAction|AutomationTagAction|AutomationFlagAction|array => self::oneOf($item, [AutomationReplyAction::class => ['type'], AutomationGroupAction::class => ['type', 'group_id'], AutomationTagAction::class => ['type', 'tag'], AutomationFlagAction::class => ['type']]), array_values($data['actions']));
        $this->schedule = $data['schedule'] ?? null;
        $this->cooldown_minutes = $data['cooldown_minutes'];
        $this->actor = isset($data['actor']) ? AutomationRuleActor::fromArray($data['actor']) : null;
        $this->created_at = self::dateTime($data['created_at']);
        $this->updated_at = self::dateTime($data['updated_at']);
    }
}
