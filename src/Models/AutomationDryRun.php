<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Enums;
use MyComm\Model;

/**
 * What the automation would do with the message. Nothing was sent or written.
 */
final class AutomationDryRun extends Model
{
    /**
     * The rule that would run; `null` for `no_match` and for a paused conversation.
     */
    public readonly ?string $matched_rule_id;

    public readonly Enums\AutomationDryRunRuleType|string|null $rule_type;

    /**
     * The rule's actions as stored on it, in order. When the run would apply but the reply is held back (the away message to an opted-out contact), the reply is left out.
     *
     * @var list<AutomationReplyAction|AutomationGroupAction|AutomationTagAction|AutomationFlagAction|array<string, mixed>>
     */
    public readonly array $actions;

    /**
     * The reply rendered for the sender, or `null` when the rule sends none.
     */
    public readonly ?AutomationReplyPreview $reply_preview;

    /**
     * `applied` would run the actions; `skipped:*` is the guard that would hold the reply back (a paused conversation is `skipped:loop_guard`); `no_match` runs nothing.
     */
    public readonly Enums\AutomationDryRunOutcome|string $outcome;

    protected function hydrate(array $data): void
    {
        $this->matched_rule_id = $data['matched_rule_id'] ?? null;
        $this->rule_type = isset($data['rule_type']) ? self::enum(Enums\AutomationDryRunRuleType::class, $data['rule_type']) : null;
        $this->actions = array_map(static fn (array $item): AutomationReplyAction|AutomationGroupAction|AutomationTagAction|AutomationFlagAction|array => self::oneOf($item, [AutomationReplyAction::class => ['type'], AutomationGroupAction::class => ['type', 'group_id'], AutomationTagAction::class => ['type', 'tag'], AutomationFlagAction::class => ['type']]), array_values($data['actions']));
        $this->reply_preview = isset($data['reply_preview']) ? AutomationReplyPreview::fromArray($data['reply_preview']) : null;
        $this->outcome = self::enum(Enums\AutomationDryRunOutcome::class, $data['outcome']);
    }
}
