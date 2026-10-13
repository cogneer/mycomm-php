<?php

declare(strict_types=1);

namespace MyComm\Generator;

/**
 * Builds a realistic payload for a schema. The testing fake answers with these, and the SDK's own tests
 * run every operation against them.
 */
final class Examples
{
    public const UUID = '01923f80-5c6a-7b10-9c21-0d3e4f5a6b7c';

    /**
     * @param  array<string, mixed>  $schema
     * @param  list<string>  $stack  Schemas being built, to stop at one that refers to itself.
     */
    public static function of(Spec $spec, array $schema, string $name = '', array $stack = [], bool $minimal = false): mixed
    {
        if (($ref = $spec->refName($schema)) !== null) {
            if (in_array($ref, $stack, true)) {
                return null;
            }

            return self::of($spec, $spec->schema($ref), $name, [...$stack, $ref], $minimal);
        }

        if ($name === 'next_cursor' || ($minimal && self::nullable($schema))) {
            return null;
        }
        if (isset($schema['examples'][0])) {
            return $schema['examples'][0];
        }
        if (array_key_exists('const', $schema)) {
            return $schema['const'];
        }

        if ($spec->isObject($schema)) {
            $example = [];
            $flat = $spec->flatten($schema);
            foreach ($flat['properties'] as $property => $definition) {
                if (! $minimal || in_array($property, $flat['required'], true)) {
                    $example[$property] = self::of($spec, $definition, (string) $property, $stack, $minimal);
                }
            }

            return $example;
        }

        if (isset($schema['oneOf']) || isset($schema['anyOf'])) {
            foreach ($schema['oneOf'] ?? $schema['anyOf'] as $variant) {
                $value = self::of($spec, $variant, $name, $stack, $minimal);
                if ($value !== null) {
                    return $value;
                }
            }

            return null;
        }

        if (isset($schema['enum'])) {
            foreach ($schema['enum'] as $value) {
                if ($value !== null) {
                    return $value;
                }
            }

            return null;
        }

        $types = array_values(array_filter((array) ($schema['type'] ?? []), static fn (string $type): bool => $type !== 'null'));

        return match ($types[0] ?? null) {
            'string' => self::string($schema, $name),
            'integer' => max(1, (int) ($schema['minimum'] ?? 1)),
            'number' => 0.5,
            'boolean' => true,
            'array' => empty($schema['items']) || $minimal ? [] : [self::of($spec, $schema['items'], Names::singular($name), $stack)],
            'object' => is_array($schema['additionalProperties'] ?? null) && $schema['additionalProperties'] !== [] && ! $minimal
                ? ['example' => self::of($spec, $schema['additionalProperties'], $name, $stack)]
                : [],
            default => null,
        };
    }

    /**
     * The sparsest payload the schema allows: optional fields left out, nullable ones null, lists empty.
     * The tests hydrate every model from it, since that is what a fresh record looks like.
     *
     * @param  array<string, mixed>  $schema
     */
    public static function minimal(Spec $spec, array $schema): mixed
    {
        return self::of($spec, $schema, minimal: true);
    }

    /**
     * @param  array<string, mixed>  $schema
     */
    private static function nullable(array $schema): bool
    {
        if (in_array('null', (array) ($schema['type'] ?? []), true) || in_array(null, $schema['enum'] ?? [], true)) {
            return true;
        }
        foreach ($schema['oneOf'] ?? $schema['anyOf'] ?? [] as $variant) {
            if (($variant['type'] ?? null) === 'null' || ($variant['enum'] ?? null) === [null]) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param  array<string, mixed>  $schema
     */
    private static function string(array $schema, string $name): string
    {
        return match ($schema['format'] ?? null) {
            'uuid' => self::UUID,
            'date-time' => '2026-10-01T09:15:02Z',
            'date' => '2026-10-01',
            default => match (true) {
                $name === 'id' || str_ends_with($name, '_id') => self::UUID,
                str_contains($name, 'url') => 'https://example.com/mycomm',
                str_contains($name, 'email') => 'ada@example.com',
                in_array($name, ['to', 'from', 'address', 'display_address', 'phone'], true) => '+923001234567',
                $name === 'country_iso' => 'PK',
                in_array($name, ['timezone', 'tz'], true) => 'Asia/Karachi',
                in_array($name, ['language', 'default_locale', 'locale'], true) => 'en',
                str_contains($name, 'token'), str_contains($name, 'secret') => 'mc_test_example',
                $name === 'mime' => 'image/jpeg',
                $name === 'sha256' => str_repeat('a', 64),
                str_ends_with($name, '_at') => '2026-10-01T09:15:02Z',
                str_ends_with($name, 'ip') => '203.0.113.10',
                $name === 'start' => '09:00',
                $name === 'end' => '21:00',
                default => $name === '' ? 'example' : 'Example '.str_replace('_', ' ', $name),
            },
        };
    }
}
