<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Models;

use MyComm\Enums;
use MyComm\Model;

/**
 * One action of a run. `status` is `queued` for a reply accepted as a message (`message_id`), `applied`
 * for any other action done or already in place (`group_id` for group actions, `tag` for a tag), `failed`
 * when the action could not run (`error`: a send-check code such as `CHANNEL_INACTIVE`, `group_not_found`,
 * `contact_not_found`, `tag_invalid`, `unknown_action`, `internal_error`, ...) and `skipped` for a reply a
 * guard held back (`reason`, the outcome without `skipped:`).
 */
final class AutomationActionResult extends Model
{
    /**
     * The action's `type` as stored on the rule.
     */
    public readonly string $type;

    public readonly Enums\AutomationActionResultStatus|string $status;

    public readonly ?string $message_id;

    public readonly ?string $group_id;

    public readonly ?string $tag;

    public readonly ?string $error;

    public readonly Enums\AutomationActionResultReason|string|null $reason;

    protected function hydrate(array $data): void
    {
        $this->type = $data['type'];
        $this->status = self::enum(Enums\AutomationActionResultStatus::class, $data['status']);
        $this->message_id = $data['message_id'] ?? null;
        $this->group_id = $data['group_id'] ?? null;
        $this->tag = $data['tag'] ?? null;
        $this->error = $data['error'] ?? null;
        $this->reason = isset($data['reason']) ? self::enum(Enums\AutomationActionResultReason::class, $data['reason']) : null;
    }
}
