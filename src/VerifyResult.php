<?php

declare(strict_types=1);

namespace MyComm;

use MyComm\Models\Account;

/**
 * What `Client::verify()` found: which checks passed and what each said.
 */
final class VerifyResult
{
    /**
     * @param  list<array{name: string, ok: bool, message: string}>  $checks
     * @param  Account|null  $account  The account the key acts on, when the key works.
     * @param  string|null  $holdReason  What would hold a message to the number you tested, when the dry run predicts a hold.
     */
    public function __construct(
        public readonly array $checks,
        public readonly ?Account $account = null,
        public readonly ?string $holdReason = null,
    ) {}

    /** Whether every check passed. */
    public function ok(): bool
    {
        foreach ($this->checks as $check) {
            if (! $check['ok']) {
                return false;
            }
        }

        return true;
    }

    /**
     * The messages of the checks that failed, for a settings screen or a log.
     *
     * @return list<string>
     */
    public function problems(): array
    {
        return array_values(array_map(static fn (array $check): string => $check['message'], array_filter($this->checks, static fn (array $check): bool => ! $check['ok'])));
    }
}
