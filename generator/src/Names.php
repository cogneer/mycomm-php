<?php

declare(strict_types=1);

namespace MyComm\Generator;

final class Names
{
    /**
     * `webhook-endpoints`, `rotate_secret`, `message.sent`, `apiKeys` => `WebhookEndpoints`, `RotateSecret`, ...
     */
    public static function pascal(string $value): string
    {
        $words = preg_split('/[^A-Za-z0-9]+/', (string) preg_replace('/([a-z0-9])([A-Z])/', '$1 $2', $value)) ?: [];

        return implode('', array_map(static fn (string $word): string => ucfirst($word), array_filter($words, static fn (string $word): bool => $word !== '')));
    }

    /**
     * The case name of an enum value: `android_sim` => `AndroidSim`, `auto:quota` => `AutoQuota`, `24h` => `V24h`.
     */
    public static function enumCase(string $value): string
    {
        $name = self::pascal(strtolower($value));
        if ($name === '') {
            return 'Empty';
        }

        return ctype_digit($name[0]) ? 'V'.$name : $name;
    }

    public static function singular(string $name): string
    {
        return match (true) {
            str_ends_with($name, 'ies') => substr($name, 0, -3).'y',
            str_ends_with($name, 'ses') => substr($name, 0, -2),
            str_ends_with($name, 's') && preg_match('/(ss|us|is)$/', $name) !== 1 => substr($name, 0, -1),
            default => $name,
        };
    }

    /**
     * A description as the lines of a docblock, wrapped and safe to embed.
     *
     * @return list<string>
     */
    public static function docLines(?string $text, int $width = 104): array
    {
        $text = trim(str_replace(['*/', "\r"], ['* /', ''], self::withoutSectionRefs((string) $text)));
        if ($text === '') {
            return [];
        }

        $lines = [];
        foreach (explode("\n\n", $text) as $index => $paragraph) {
            if ($index > 0) {
                $lines[] = '';
            }
            $paragraph = trim((string) preg_replace('/\s+/', ' ', $paragraph));
            foreach (explode("\n", wordwrap($paragraph, $width, "\n", false)) as $line) {
                // A line that starts with "@" would be read as a docblock tag.
                $lines[] = str_starts_with($line, '@') ? ' '.$line : $line;
            }
        }

        return $lines;
    }

    /**
     * Drops the pointers into the internal design documents, such as ` (03 section 2.5)`: they mean nothing
     * to a reader of the SDK.
     */
    private static function withoutSectionRefs(string $text): string
    {
        $ref = '\d{2} sections? \d+(?:\.\d+)*(?: and \d+(?:\.\d+)*)?';

        return (string) preg_replace('/\s*\((?:'.$ref.')(?:,\s*'.$ref.')*\)/', '', $text);
    }

    /**
     * A one-line description for a `@param` or `@var` tag.
     */
    public static function inline(?string $text, int $limit = 220): string
    {
        $text = trim((string) preg_replace('/\s+/', ' ', str_replace('*/', '* /', self::withoutSectionRefs((string) $text))));

        return mb_strlen($text) > $limit ? rtrim(mb_substr($text, 0, $limit - 1)).'…' : $text;
    }
}
