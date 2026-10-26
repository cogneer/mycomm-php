<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use DateTimeImmutable;
use MyComm\Enums;
use MyComm\Model;

/**
 * One evaluation of a rule, append-only.
 */
final class AutomationRun extends Model
{
    /**
     * Increasing run id.
     */
    public readonly int $id;

    public readonly string $rule_id;

    public readonly string $inbox_id;

    public readonly string $conversation_id;

    public readonly string $trigger_message_id;

    public readonly Enums\AutomationRunOutcome|string $outcome;

    /**
     * What each action of the rule did, one element per action in the rule's order. A guard skip lists the held-back reply as `skipped` and the other actions as they ran; the away message to an opted-out contact lists no repl…
     */
    public readonly mixed $actions_result;

    public readonly DateTimeImmutable $created_at;

    protected function hydrate(array $data): void
    {
        $this->id = $data['id'];
        $this->rule_id = $data['rule_id'];
        $this->inbox_id = $data['inbox_id'];
        $this->conversation_id = $data['conversation_id'];
        $this->trigger_message_id = $data['trigger_message_id'];
        $this->outcome = self::enum(Enums\AutomationRunOutcome::class, $data['outcome']);
        $this->actions_result = $data['actions_result'] ?? null;
        $this->created_at = self::dateTime($data['created_at']);
    }
}
