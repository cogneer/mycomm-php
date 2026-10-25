<?php

declare(strict_types=1);

namespace MyComm\Exceptions;

/**
 * A request was refused before it was sent, because an argument breaks a rule the API documents (a value out of range,
 * a missing recipient, two fields that exclude each other). No request was made, so it is not an `ApiException`.
 *
 * `field` and `rule` describe the first problem; `violations()` lists all of them. The rules are named like the codes of
 * the API's own `VALIDATION_FAILED` answer (`OUT_OF_RANGE`, `TOO_LONG`, `EXCLUSIVE`, `REQUIRED_WITH`, ...). Turn the checks
 * off with `new Client(..., validate: false)` if the API has relaxed a rule your SDK version does not know yet.
 */
final class InvalidArgumentException extends MyCommException
{
    /**
     * @param  string  $operation  The SDK call, for example `messages.send`.
     * @param  list<array{field: string, rule: string, message: string}>  $violations  Every problem found, in the order the arguments are declared.
     */
    public function __construct(public readonly string $operation, public readonly array $violations)
    {
        $first = $violations[0] ?? ['field' => '', 'rule' => '', 'message' => 'The arguments are not valid.'];
        $more = count($violations) - 1;
        parent::__construct($first['message'].($more > 0 ? " (and {$more} more: ".implode(' ', array_map(static fn (array $violation): string => $violation['message'], array_slice($violations, 1, 2))).')' : ''));
        $this->field = $first['field'];
        $this->rule = $first['rule'];
    }

    /** The field (argument) of the first problem. */
    public readonly string $field;

    /** The rule of the first problem, for example `OUT_OF_RANGE`. */
    public readonly string $rule;
}
