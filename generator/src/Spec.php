<?php

declare(strict_types=1);

namespace MyComm\Generator;

use RuntimeException;
use Symfony\Component\Yaml\Yaml;

/**
 * One bundled OpenAPI document and the lookups the generator needs on it.
 */
final class Spec
{
    /** @var array<string, mixed> */
    public readonly array $doc;

    /** Path prefix of the API below the origin, for example `/v1`. */
    public readonly string $prefix;

    public function __construct(public readonly string $name, string $file)
    {
        $doc = Yaml::parseFile($file);
        if (! is_array($doc) || ! isset($doc['paths'])) {
            throw new RuntimeException("{$file} is not an OpenAPI document.");
        }
        $this->doc = $doc;
        $this->prefix = rtrim((string) parse_url((string) ($doc['servers'][0]['url'] ?? ''), PHP_URL_PATH), '/');
    }

    public function version(): string
    {
        return (string) ($this->doc['info']['version'] ?? '0');
    }

    /**
     * @return array<string, mixed>
     */
    public function schema(string $name): array
    {
        return $this->doc['components']['schemas'][$name]
            ?? throw new RuntimeException("Schema {$name} is not defined in the {$this->name} spec.");
    }

    public function hasSchema(string $name): bool
    {
        return isset($this->doc['components']['schemas'][$name]);
    }

    /**
     * The schema name a `$ref` points at, or null for an inline schema.
     *
     * @param  array<string, mixed>  $schema
     */
    public function refName(array $schema): ?string
    {
        $ref = $schema['$ref'] ?? null;
        if (! is_string($ref)) {
            return null;
        }
        if (! str_starts_with($ref, '#/components/schemas/')) {
            throw new RuntimeException("Unsupported schema reference {$ref}.");
        }

        return substr($ref, strlen('#/components/schemas/'));
    }

    /**
     * Follows `$ref`s until an inline schema is reached.
     *
     * @param  array<string, mixed>  $schema
     * @return array<string, mixed>
     */
    public function resolve(array $schema): array
    {
        while (($name = $this->refName($schema)) !== null) {
            $schema = $this->schema($name);
        }

        return $schema;
    }

    /**
     * An object schema with every `allOf` member merged in.
     *
     * @param  array<string, mixed>  $schema
     * @return array{properties: array<string, array<string, mixed>>, required: list<string>, description: ?string, additionalProperties: mixed, origins: array<string, string>}
     */
    public function flatten(array $schema): array
    {
        // The named schema a property is declared in, so an object nested in it gets one class wherever
        // the schema is merged into another.
        $origin = null;
        while (($name = $this->refName($schema)) !== null) {
            $origin = $name;
            $schema = $this->schema($name);
        }

        $flat = [
            'properties' => [],
            'required' => [],
            'description' => $schema['description'] ?? null,
            'additionalProperties' => $schema['additionalProperties'] ?? null,
            'origins' => [],
        ];

        foreach ($schema['allOf'] ?? [] as $member) {
            $part = $this->flatten($member);
            // A later member narrows a property of an earlier one (the event `data` of a webhook envelope).
            $flat['properties'] = array_merge($flat['properties'], $part['properties']);
            $flat['origins'] = array_merge($flat['origins'], $part['origins']);
            $flat['required'] = array_merge($flat['required'], $part['required']);
            $flat['description'] ??= $part['description'];
            $flat['additionalProperties'] ??= $part['additionalProperties'];
        }

        foreach ($schema['properties'] ?? [] as $property => $definition) {
            $flat['properties'][$property] = $definition;
            unset($flat['origins'][$property]);
            if ($origin !== null) {
                $flat['origins'][$property] = $origin;
            }
        }
        $flat['required'] = array_values(array_unique(array_merge($flat['required'], $schema['required'] ?? [])));

        return $flat;
    }

    /**
     * The schema an `allOf` with a single member stands for: `{allOf: [{$ref: X}], description: ...}` is X.
     *
     * @param  array<string, mixed>  $schema
     * @return array<string, mixed>|null
     */
    public function alias(array $schema): ?array
    {
        if (count($schema['allOf'] ?? []) !== 1 || array_diff(array_keys($schema), ['allOf', 'description', 'examples', 'title']) !== []) {
            return null;
        }

        return $schema['allOf'][0];
    }

    /**
     * Whether a schema describes an object with named fields (directly or through `allOf`).
     *
     * @param  array<string, mixed>  $schema
     */
    public function isObject(array $schema): bool
    {
        $schema = $this->resolve($schema);
        if (isset($schema['properties'])) {
            return true;
        }
        foreach ($schema['allOf'] ?? [] as $member) {
            if ($this->isObject($member)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param  array<string, mixed>  $parameter
     * @return array<string, mixed>
     */
    public function parameter(array $parameter): array
    {
        $ref = $parameter['$ref'] ?? null;
        if (! is_string($ref)) {
            return $parameter;
        }

        $name = substr($ref, strlen('#/components/parameters/'));

        return $this->doc['components']['parameters'][$name]
            ?? throw new RuntimeException("Parameter {$name} is not defined in the {$this->name} spec.");
    }

    /**
     * Every operation: [path, lower-case method, operation, path item].
     *
     * @return list<array{string, string, array<string, mixed>, array<string, mixed>}>
     */
    public function operations(): array
    {
        $operations = [];
        foreach ($this->doc['paths'] as $path => $item) {
            foreach (['get', 'post', 'put', 'patch', 'delete'] as $method) {
                if (isset($item[$method])) {
                    $operations[] = [(string) $path, $method, $item[$method], $item];
                }
            }
        }

        return $operations;
    }

    /**
     * Webhook event type => the schema of its body.
     *
     * @return array<string, array<string, mixed>>
     */
    public function webhooks(): array
    {
        $events = [];
        foreach ($this->doc['webhooks'] ?? [] as $type => $item) {
            $schema = $item['post']['requestBody']['content']['application/json']['schema'] ?? null;
            if (is_array($schema)) {
                $events[(string) $type] = $schema + ['x-summary' => $item['post']['description'] ?? null];
            }
        }

        return $events;
    }
}
