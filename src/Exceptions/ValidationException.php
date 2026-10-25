<?php

declare(strict_types=1);

namespace MyComm\Exceptions;

/**
 * 422 `VALIDATION_FAILED`: one or more fields are invalid.
 */
final class ValidationException extends UnprocessableException
{
    /**
     * Violations per field path, for example `['to' => [['code' => 'INVALID_NUMBER', 'message' => '...']]]`.
     *
     * @return array<string, list<array{code: string, message: string}>>
     */
    public function fields(): array
    {
        $fields = [];
        foreach (is_array($this->details['fields'] ?? null) ? $this->details['fields'] : [] as $path => $violations) {
            foreach (is_array($violations) ? $violations : [] as $violation) {
                $fields[(string) $path][] = [
                    'code' => (string) ($violation['code'] ?? ''),
                    'message' => (string) ($violation['message'] ?? ''),
                ];
            }
        }

        return $fields;
    }

    protected function summaryDetails(): string
    {
        $lines = [];
        foreach ($this->fields() as $field => $violations) {
            foreach ($violations as $violation) {
                $lines[] = "{$field}: {$violation['code']}: {$violation['message']}";
            }
        }

        return implode('; ', $lines);
    }

    /**
     * The messages of one field, empty when the field is valid.
     *
     * @return list<string>
     */
    public function messagesFor(string $field): array
    {
        return array_column($this->fields()[$field] ?? [], 'message');
    }
}
