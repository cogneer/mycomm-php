<?php

declare(strict_types=1);

namespace MyComm\Generator;

use RuntimeException;

/**
 * Reads the operations of a spec into the shape the resource emitter works from.
 */
final class Operations
{
    /**
     * @param  array<string, string>  $multipartNames  SDK operation name => method name of its multipart variant.
     * @param  array<string, string>  $operationNames  The document's operation id => the SDK's name for it (`resource.method`).
     */
    public function __construct(private readonly Types $types, private readonly array $multipartNames = [], private readonly array $operationNames = []) {}

    /**
     * @return list<array<string, mixed>>
     */
    public function read(Spec $spec): array
    {
        $operations = [];
        foreach ($spec->operations() as [$path, $method, $operation, $item]) {
            foreach ($this->operation($spec, $path, $method, $operation, $item) as $variant) {
                $operations[] = $variant;
            }
        }

        return $operations;
    }

    /**
     * One entry per request body format of the operation (most have one).
     *
     * @param  array<string, mixed>  $operation
     * @param  array<string, mixed>  $item
     * @return list<array<string, mixed>>
     */
    private function operation(Spec $spec, string $path, string $method, array $operation, array $item): array
    {
        $documentId = (string) ($operation['operationId'] ?? throw new RuntimeException("{$method} {$path} has no operationId."));
        // A table is required for the real documents; a spec without one (a test fixture) keeps its ids.
        $id = $this->operationNames === []
            ? $documentId
            : ($this->operationNames[$documentId] ?? throw new RuntimeException("{$method} {$path} ({$documentId}) has no SDK name: add it to 'operations' in generator/config.php."));
        $segments = explode('.', $id);
        $name = (string) array_pop($segments);
        $context = Names::pascal($id);

        preg_match_all('/\{([^}]+)\}/', $path, $matches);

        $base = [
            'id' => $id,
            'documentId' => $documentId,
            'resource' => $segments,
            'http' => strtoupper($method),
            'path' => $spec->prefix.$path,
            'pathParams' => $matches[1],
            'summary' => $operation['summary'] ?? null,
            'description' => $operation['description'] ?? null,
            'deprecated' => (bool) ($operation['deprecated'] ?? false),
            'query' => $this->query($spec, array_merge($item['parameters'] ?? [], $operation['parameters'] ?? []), $context, $id),
            'responses' => $this->responses($spec, $operation['responses'] ?? [], $context, $id),
        ];

        $bodies = [];
        $rules = [];
        foreach ($operation['requestBody']['content'] ?? [] as $contentType => $media) {
            $schema = $media['schema'] ?? [];
            $ref = $spec->refName($schema);
            $format = str_starts_with((string) $contentType, 'multipart/') ? 'multipart' : 'json';
            $rules[$format] = $format === 'json' ? $this->types->rules($spec, $schema) : [];
            $bodies[str_starts_with((string) $contentType, 'multipart/') ? 'multipart' : 'json'] = $this->types->fields(
                $spec,
                $schema,
                $ref === null ? $context : (string) preg_replace('/Request$/', '', $ref),
                $ref ?? $context,
            );
        }

        if ($bodies === []) {
            return [$base + ['name' => $name, 'format' => 'none', 'fields' => [], 'rules' => []]];
        }

        $variants = [];
        foreach ($bodies as $format => $fields) {
            $variantName = $name;
            if ($format === 'multipart' && count($bodies) > 1) {
                $variantName = $this->multipartNames[$id]
                    ?? throw new RuntimeException("{$id} takes JSON and multipart bodies: name its multipart method in generator/config.php.");
            }
            $variants[] = $base + ['name' => $variantName, 'format' => $format, 'fields' => $fields, 'rules' => $rules[$format] ?? []];
        }

        return $variants;
    }

    /**
     * @param  array<int, array<string, mixed>>  $parameters
     * @return array<string, array{type: TypeInfo, required: bool, description: ?string}>
     */
    private function query(Spec $spec, array $parameters, string $context, string $id): array
    {
        $query = [];
        foreach ($parameters as $parameter) {
            $parameter = $spec->parameter($parameter);
            if (($parameter['in'] ?? null) !== 'query') {
                continue;
            }

            $name = (string) $parameter['name'];
            $schema = $parameter['schema'] ?? [];
            $description = $parameter['description'] ?? null;

            // A filter is a plain string on the wire (several values are comma-separated), so an inline
            // list of values is documented instead of turned into an enum class.
            if (isset($schema['enum'])) {
                $values = implode(', ', array_map(static fn (mixed $value): string => '`'.$value.'`', array_filter($schema['enum'], is_string(...))));
                $description = trim(($description ?? '').' One of: '.$values.'.');
                $type = new TypeInfo('string', 'string');
            } elseif (($schema['type'] ?? null) === 'array' && ($parameter['explode'] ?? true) === false) {
                // A comma list (`style: form, explode: false`): pass a list, or the comma-separated text itself.
                $items = $spec->resolve($schema['items'] ?? []);
                if (isset($items['enum'])) {
                    $values = implode(', ', array_map(static fn (mixed $value): string => '`'.$value.'`', array_filter($items['enum'], is_string(...))));
                    $description = trim(($description ?? '').' Values: '.$values.'.');
                }
                $type = new TypeInfo('array|string', 'list<string>|string');
            } else {
                $type = $this->types->input($spec, $schema, $context.Names::pascal($name), $id.'?'.$name);
            }

            // A filter's values may grow on the server before this SDK does, so only limits are kept, never its list of values.
            $constraints = array_diff_key($this->types->constraints($spec, $schema), ['enum' => true]);
            $query[$name] = ['type' => $type, 'required' => (bool) ($parameter['required'] ?? false), 'description' => $description, 'constraints' => $constraints];
        }

        // Filters first, then the paging pair.
        $paging = array_intersect_key($query, ['limit' => true, 'cursor' => true]);

        return [...array_diff_key($query, $paging), ...$paging];
    }

    /**
     * The success answers by status: nothing, one page of a list, or an object.
     *
     * @param  array<int|string, array<string, mixed>>  $responses
     * @return array<int, array{kind: string, type?: TypeInfo, item?: TypeInfo, schema?: array<string, mixed>}>
     */
    private function responses(Spec $spec, array $responses, string $context, string $id): array
    {
        $success = [];
        foreach ($responses as $status => $response) {
            $status = (int) $status;
            if ($status < 200 || $status > 299) {
                continue;
            }

            $schema = $response['content']['application/json']['schema'] ?? null;
            if (! is_array($schema)) {
                $success[$status] = ['kind' => 'none'];

                continue;
            }

            if ($spec->refName($schema) === null) {
                $flat = $spec->flatten($schema);
                $data = isset($flat['properties']['data']) ? $spec->resolve($flat['properties']['data']) : null;
                if ($data !== null && ($data['type'] ?? null) === 'array' && array_diff(array_keys($flat['properties']), ['data', 'next_cursor']) === []) {
                    $success[$status] = [
                        'kind' => 'page',
                        'item' => $this->types->output($spec, $data['items'] ?? [], $context.'Item', $id.'[]'),
                        'schema' => $schema,
                    ];

                    continue;
                }
            }

            $success[$status] = [
                'kind' => 'model',
                'type' => $this->types->output($spec, $schema, $context.'Response', $id.'()'),
                'schema' => $schema,
            ];
        }
        ksort($success);

        return $success;
    }
}
