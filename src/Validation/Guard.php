<?php

declare(strict_types=1);

namespace MyComm\Validation;

use BackedEnum;
use MyComm\Exceptions\InvalidArgumentException;
use MyComm\Omit;

/**
 * The checks the API documents, run on the arguments of a call before anything is sent. The constraints and the
 * cross-field rules come from the OpenAPI document (`generator/` writes them into each resource method), so the SDK cannot
 * drift from the contract by hand.
 *
 * A value that is `null` or `Omit::Value` is "not sent" and is never checked: PHP's own types already insist on required
 * arguments. Values the server may extend later, such as the enum of a response, are not checked at all; only what the caller
 * sends.
 *
 * @internal
 */
final class Guard
{
    private const UUID = '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i';

    /**
     * @param  array<string, mixed>  $values  The arguments by field name.
     * @param  array<string, array<string, mixed>>  $constraints  Field name => constraints (`min`, `max`, `minLength`, `maxLength`, `pattern`, `enum`, `uuid`, `minItems`, `maxItems`, `maxProperties`, `scalarValues`, `maxBytes`).
     * @param  list<array<int, mixed>>  $rules  Cross-field rules: `['exactlyOne', [fields]]`, `['atLeastOne', [fields]]`, `['requires', field, other]`, `['requiredWhen', field, whenField, value]`.
     *
     * @throws InvalidArgumentException
     */
    public static function check(string $operation, array $values, array $constraints, array $rules): void
    {
        $violations = [];

        foreach ($constraints as $field => $constraint) {
            $value = $values[$field] ?? null;
            if (self::absent($value)) {
                continue;
            }
            foreach (self::field((string) $field, self::plain($value), $constraint) as $violation) {
                $violations[] = $violation;
            }
        }

        foreach ($rules as $rule) {
            $violation = self::rule($values, $rule);
            if ($violation !== null) {
                $violations[] = $violation;
            }
        }

        if ($violations !== []) {
            throw new InvalidArgumentException($operation, $violations);
        }
    }

    /**
     * @param  array<string, mixed>  $constraint
     * @return list<array{field: string, rule: string, message: string}>
     */
    private static function field(string $field, mixed $value, array $constraint): array
    {
        $found = [];
        $add = static function (string $rule, string $message) use (&$found, $field): void {
            $found[] = ['field' => $field, 'rule' => $rule, 'message' => $message];
        };

        if (isset($constraint['enum']) && is_scalar($value) && ! in_array((string) $value, array_map('strval', $constraint['enum']), true)) {
            $add('INVALID_ENUM', "{$field} must be one of ".implode(', ', $constraint['enum']).' (got '.self::show($value).').');
        }

        if (is_int($value) || is_float($value)) {
            $min = $constraint['min'] ?? null;
            $max = $constraint['max'] ?? null;
            if (($min !== null && $value < $min) || ($max !== null && $value > $max)) {
                $range = match (true) {
                    $min !== null && $max !== null => "between {$min} and {$max}",
                    $min !== null => "at least {$min}",
                    default => "at most {$max}",
                };
                $add('OUT_OF_RANGE', "{$field} must be {$range} (got {$value}).");
            }
        }

        if (is_string($value)) {
            $length = mb_strlen($value);
            if (isset($constraint['maxLength']) && $length > $constraint['maxLength']) {
                $add('TOO_LONG', "{$field} must be at most {$constraint['maxLength']} characters (got {$length}).");
            }
            if (isset($constraint['minLength']) && $length < $constraint['minLength']) {
                $add('TOO_SHORT', "{$field} must be at least {$constraint['minLength']} characters (got {$length}).");
            }
            if (isset($constraint['pattern']) && @preg_match('/'.str_replace('/', '\/', $constraint['pattern']).'/u', $value) === 0) {
                $add('INVALID_FORMAT', "{$field} is not in the expected format ({$constraint['pattern']}).");
            }
            if (($constraint['uuid'] ?? false) === true && preg_match(self::UUID, $value) !== 1) {
                $add('INVALID_FORMAT', "{$field} must be a UUID (got ".self::show($value).').');
            }
        }

        if (is_array($value)) {
            $count = count($value);
            if (isset($constraint['maxItems']) && $count > $constraint['maxItems']) {
                $add('TOO_LONG', "{$field} may hold at most {$constraint['maxItems']} items (got {$count}).");
            }
            if (isset($constraint['minItems']) && $count < $constraint['minItems']) {
                $add('TOO_SHORT', "{$field} needs at least {$constraint['minItems']} items (got {$count}).");
            }
            if (isset($constraint['maxProperties']) && $count > $constraint['maxProperties']) {
                $add('TOO_LONG', "{$field} may hold at most {$constraint['maxProperties']} keys (got {$count}).");
            }
            if (($constraint['uuid'] ?? false) === true) {
                foreach ($value as $item) {
                    if (is_string($item) && preg_match(self::UUID, $item) !== 1) {
                        $add('INVALID_FORMAT', "{$field} must hold UUIDs (got ".self::show($item).').');
                        break;
                    }
                }
            }
            if (($constraint['scalarValues'] ?? false) === true) {
                foreach ($value as $key => $item) {
                    if (! is_scalar($item) && $item !== null) {
                        $add('INVALID_TYPE', "{$field} may only hold text, number, boolean or null values; '{$key}' does not.");
                        break;
                    }
                }
            }
            if (isset($constraint['maxBytes'])) {
                $bytes = strlen((string) json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
                if ($bytes > $constraint['maxBytes']) {
                    $add('TOO_LONG', "{$field} may be at most {$constraint['maxBytes']} bytes as JSON (got {$bytes}).");
                }
            }
        }

        return $found;
    }

    /**
     * @param  array<string, mixed>  $values
     * @param  array<int, mixed>  $rule
     * @return array{field: string, rule: string, message: string}|null
     */
    private static function rule(array $values, array $rule): ?array
    {
        $present = static fn (string $field): bool => ! self::absent($values[$field] ?? null);

        switch ($rule[0]) {
            case 'exactlyOne':
                $fields = $rule[1];
                $given = array_values(array_filter($fields, $present));
                if (count($given) === 1) {
                    return null;
                }

                return count($given) === 0
                    ? ['field' => $fields[0], 'rule' => 'REQUIRED_WITH', 'message' => 'Give one of '.implode(', ', $fields).'.']
                    : ['field' => $given[1], 'rule' => 'EXCLUSIVE', 'message' => 'Give only one of '.implode(', ', $fields).', not '.implode(' and ', $given).'.'];

            case 'atLeastOne':
                $fields = $rule[1];

                return array_filter($fields, $present) === []
                    ? ['field' => $fields[0], 'rule' => 'REQUIRED_WITH', 'message' => 'Give at least one of '.implode(', ', $fields).'.']
                    : null;

            case 'requires':
                return $present($rule[1]) && ! $present($rule[2])
                    ? ['field' => $rule[1], 'rule' => 'REQUIRED_WITH', 'message' => "{$rule[1]} needs {$rule[2]}."]
                    : null;

            case 'requiredWhen':
                $condition = $values[$rule[2]] ?? null;
                $condition = $condition instanceof BackedEnum ? $condition->value : $condition;

                return ! $present($rule[1]) && $condition === $rule[3]
                    ? ['field' => $rule[1], 'rule' => 'REQUIRED_WHEN', 'message' => "{$rule[1]} is required when {$rule[2]} is {$rule[3]}."]
                    : null;
        }

        return null;
    }

    private static function absent(mixed $value): bool
    {
        return $value === null || $value === Omit::Value;
    }

    /** An enum case is checked by its value. */
    private static function plain(mixed $value): mixed
    {
        return $value instanceof BackedEnum ? $value->value : $value;
    }

    private static function show(mixed $value): string
    {
        return is_string($value) ? "'".mb_substr($value, 0, 40)."'" : (string) json_encode($value);
    }
}
