<?php

declare(strict_types=1);

namespace MyComm\Generator;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RuntimeException;

/**
 * Generates everything of the SDK that follows from the API reference: resources, models, enums, inputs,
 * webhook events, the examples of the testing fake and the dataset the tests run every operation with.
 */
final class Generator
{
    /** Directories and files the generator owns: emptied before each run so nothing stale survives. */
    private const OWNED = [
        'src/Models', 'src/Enums', 'src/Inputs', 'src/Resources', 'src/Webhooks/Events',
        'src/Webhooks/Event.php', 'src/Webhooks/EventMap.php', 'src/Testing/Examples.php', 'tests/Generated',
    ];

    /** @var array<string, mixed> */
    private readonly array $config;

    /** @var array<string, string> Relative path => contents. */
    private array $files = [];

    public function __construct(private readonly string $root)
    {
        $this->config = require $root.'/generator/config.php';
    }

    /**
     * @return array<string, int> What was written, for the summary line.
     */
    public function run(): array
    {
        $types = new Types($this->config['enums'], $this->config['models'], $this->config['nullable']);
        $emitter = new Emitter;
        $reader = new Operations($types, $this->config['multipart']);

        /** @var array<string, Spec> $specs */
        $specs = [];
        $operations = [];
        foreach ($this->config['specs'] as $name => $file) {
            $specs[$name] = new Spec($name, $this->root.'/'.$file);
            array_push($operations, ...array_map(static fn (array $operation): array => $operation + ['spec' => $name], $reader->read($specs[$name])));
        }
        $main = reset($specs) ?: throw new RuntimeException('No spec is configured.');

        // The exceptions compare against it, so it exists even when no answer refers to it.
        $types->output($main, ['$ref' => '#/components/schemas/ErrorCode'], 'ErrorCode', 'ErrorCode');

        $events = $this->events($main, $types, $emitter);
        $this->resources($operations, $emitter);

        foreach ($types->models as $name => $model) {
            $this->files["src/Models/{$name}.php"] = $emitter->model($name, $model);
        }
        foreach ($types->enums as $name => $enum) {
            $this->files["src/Enums/{$name}.php"] = $emitter->enum($name, $enum);
        }
        foreach ($types->inputs as $name => $input) {
            $this->files["src/Inputs/{$name}.php"] = $emitter->input($name, $input);
        }

        $unused = array_diff_key($this->config['nullable'], $types->corrected);
        if ($unused !== []) {
            throw new RuntimeException('These corrections in generator/config.php match no field of the spec: '.implode(', ', array_keys($unused)).'.');
        }

        $responses = $this->responseExamples($operations, $specs);
        $this->files['src/Testing/Examples.php'] = $emitter->examples($responses, $events);
        $this->files['tests/Generated/operations.php'] = $emitter->data($this->dataset($operations, $specs, $responses));
        $this->files['tests/Generated/minimal.php'] = $emitter->data($this->minimal($operations, $specs, $main));

        $this->write();

        return [
            'operations' => count($operations),
            'models' => count($types->models),
            'enums' => count($types->enums),
            'inputs' => count($types->inputs),
            'events' => count($events),
            'files' => count($this->files),
        ];
    }

    /**
     * @param  list<array<string, mixed>>  $operations
     */
    private function resources(array $operations, Emitter $emitter): void
    {
        // Keyed by resource path ("contacts", "contacts.identities"): its segments, operations and children.
        /** @var array<string, list<string>> $paths */
        $paths = [];
        /** @var array<string, list<array<string, mixed>>> $methods */
        $methods = [];
        /** @var array<string, array<string, true>> $children */
        $children = [];
        foreach ($operations as $operation) {
            /** @var list<string> $segments */
            $segments = $operation['resource'];
            if ($segments === []) {
                throw new RuntimeException("{$operation['id']} has no resource segment.");
            }
            for ($depth = 1; $depth <= count($segments); $depth++) {
                $key = implode('.', array_slice($segments, 0, $depth));
                $paths[$key] = array_slice($segments, 0, $depth);
                $methods[$key] ??= [];
                $children[$key] ??= [];
                if ($depth < count($segments)) {
                    $children[$key][$segments[$depth]] = true;
                }
            }
            $methods[implode('.', $segments)][] = $operation;
        }

        $top = [];
        foreach ($paths as $key => $path) {
            $names = array_column($methods[$key], 'name');
            if (count($names) !== count(array_unique($names)) || array_intersect($names, array_keys($children[$key])) !== []) {
                throw new RuntimeException("Two operations of {$key} would get the same method name.");
            }

            $file = 'src/Resources/'.implode('/', array_map(Names::pascal(...), $path)).'.php';
            $this->files[$file] = $emitter->resource($path, $methods[$key], array_keys($children[$key]));
            if (count($path) === 1) {
                $top[] = $path[0];
            }
        }

        $this->files['src/Resources/Concerns/ProvidesResources.php'] = $emitter->clientResources($top);
    }

    /**
     * @return array<string, mixed> Event type => example payload.
     */
    private function events(Spec $spec, Types $types, Emitter $emitter): array
    {
        $webhooks = $spec->webhooks();
        if ($webhooks === []) {
            return [];
        }

        $envelope = $spec->flatten(['$ref' => '#/components/schemas/WebhookEnvelope']);
        $base = [];
        foreach ($envelope['properties'] as $property => $definition) {
            if ($property !== 'data') {
                $base[$property] = [
                    'type' => $types->output($spec, $definition, 'WebhookEnvelope'.Names::pascal($property), 'WebhookEnvelope.'.$property),
                    'required' => in_array($property, $envelope['required'], true),
                    'description' => $definition['description'] ?? null,
                ];
            }
        }
        $this->files['src/Webhooks/Event.php'] = $emitter->eventBase($base);

        $map = [];
        $examples = [];
        foreach ($webhooks as $type => $schema) {
            $description = $schema['x-summary'];
            unset($schema['x-summary']);

            $flat = $spec->flatten($schema);
            $extra = array_diff(array_keys($flat['properties']), array_keys($envelope['properties']));
            if ($extra !== []) {
                throw new RuntimeException("Webhook {$type} has fields outside the envelope: ".implode(', ', $extra).'.');
            }

            $class = Names::pascal($type);
            $data = $types->output($spec, $flat['properties']['data'] ?? [], $class.'Data', 'webhook:'.$type.'.data');
            $this->files["src/Webhooks/Events/{$class}.php"] = $emitter->event($class, $type, $description, $data);
            $map[$type] = $class;
            $examples[$type] = Examples::of($spec, $schema);
        }
        $this->files['src/Webhooks/EventMap.php'] = $emitter->eventMap($map);

        return $examples;
    }

    /**
     * The answer the fake gives per operation: the highest documented success status.
     *
     * @param  list<array<string, mixed>>  $operations
     * @param  array<string, Spec>  $specs
     * @return array<string, array{status: int, body: mixed}>
     */
    private function responseExamples(array $operations, array $specs): array
    {
        $examples = [];
        foreach ($operations as $operation) {
            if ($operation['responses'] === []) {
                continue;
            }
            $status = (int) array_key_last($operation['responses']);
            $response = $operation['responses'][$status];
            $examples[$operation['id']] = [
                'status' => $status,
                'body' => isset($response['schema']) ? Examples::of($specs[$operation['spec']], $response['schema']) : null,
            ];
        }
        ksort($examples);

        return $examples;
    }

    /**
     * The sparsest success answer of every status of every operation, and the sparsest payload of every event.
     *
     * @param  list<array<string, mixed>>  $operations
     * @param  array<string, Spec>  $specs
     * @return array{responses: list<array{operation: string, status: int, body: mixed}>, events: array<string, mixed>}
     */
    private function minimal(array $operations, array $specs, Spec $main): array
    {
        $responses = [];
        foreach ($operations as $operation) {
            foreach ($operation['responses'] as $status => $response) {
                if (isset($response['schema'])) {
                    $responses[] = ['operation' => $operation['id'], 'status' => $status, 'body' => Examples::minimal($specs[$operation['spec']], $response['schema'])];
                }
            }
        }

        $events = [];
        foreach ($main->webhooks() as $type => $schema) {
            unset($schema['x-summary']);
            $events[$type] = Examples::minimal($main, $schema);
        }

        return ['responses' => $responses, 'events' => $events];
    }

    /**
     * One row per method: how to call it with example arguments and what must come out.
     *
     * @param  list<array<string, mixed>>  $operations
     * @param  array<string, Spec>  $specs
     * @param  array<string, array{status: int, body: mixed}>  $responses
     * @return list<array<string, mixed>>
     */
    private function dataset(array $operations, array $specs, array $responses): array
    {
        $rows = [];
        foreach ($operations as $operation) {
            $spec = $specs[$operation['spec']];
            $arguments = [];
            $path = $operation['path'];
            foreach ($operation['pathParams'] as $name) {
                $arguments[$name] = Examples::UUID;
                $path = str_replace('{'.$name.'}', Examples::UUID, $path);
            }

            $source = $this->requestSchemas($spec, $operation);
            foreach ($operation['fields'] as $name => $field) {
                if ($field['required']) {
                    $arguments[$name] = $field['type']->file ? '@file' : Examples::of($spec, $source['fields'][$name] ?? [], (string) $name);
                }
            }
            foreach ($operation['query'] as $name => $parameter) {
                if ($parameter['required']) {
                    $arguments[$name] = Examples::of($spec, $source['query'][$name] ?? [], (string) $name);
                }
            }

            $example = $responses[$operation['id']] ?? null;
            $response = $example === null ? null : $operation['responses'][$example['status']];
            $rows[] = [
                'operation' => $operation['id'],
                'resource' => $operation['resource'],
                'method' => $operation['name'],
                'arguments' => $arguments,
                'http' => $operation['http'],
                'path' => $path,
                'format' => $operation['format'],
                'returns' => match (true) {
                    $response === null => 'json',
                    $response['kind'] === 'none' => array_filter($operation['responses'], static fn (array $r): bool => $r['kind'] !== 'none') === [] ? 'void' : 'null',
                    $response['kind'] === 'page' => 'page',
                    default => $response['type']->model === null ? 'json' : 'MyComm\\'.$response['type']->php,
                },
                'item' => $response !== null && $response['kind'] === 'page' && $response['item']->model !== null ? 'MyComm\\'.$response['item']->php : null,
            ];
        }

        return $rows;
    }

    /**
     * The raw schemas behind the fields and query parameters of an operation, for building example arguments.
     *
     * @param  array<string, mixed>  $operation
     * @return array{fields: array<string, array<string, mixed>>, query: array<string, array<string, mixed>>}
     */
    private function requestSchemas(Spec $spec, array $operation): array
    {
        $path = substr($operation['path'], strlen($spec->prefix));
        $item = $spec->doc['paths'][$path];
        $definition = $item[strtolower($operation['http'])];

        $fields = [];
        foreach ($definition['requestBody']['content'] ?? [] as $contentType => $media) {
            if ((str_starts_with((string) $contentType, 'multipart/') ? 'multipart' : 'json') === $operation['format']) {
                $fields = $spec->flatten($media['schema'] ?? [])['properties'];
            }
        }

        $query = [];
        foreach (array_merge($item['parameters'] ?? [], $definition['parameters'] ?? []) as $parameter) {
            $parameter = $spec->parameter($parameter);
            if (($parameter['in'] ?? null) === 'query') {
                $query[(string) $parameter['name']] = $parameter['schema'] ?? [];
            }
        }

        return ['fields' => $fields, 'query' => $query];
    }

    private function write(): void
    {
        foreach (self::OWNED as $owned) {
            $target = $this->root.'/'.$owned;
            if (is_file($target)) {
                unlink($target);
            } elseif (is_dir($target)) {
                $entries = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($target, RecursiveDirectoryIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST);
                foreach ($entries as $entry) {
                    $entry->isDir() ? rmdir($entry->getPathname()) : unlink($entry->getPathname());
                }
            }
        }

        foreach ($this->files as $path => $contents) {
            $target = $this->root.'/'.$path;
            if (! is_dir(dirname($target))) {
                mkdir(dirname($target), 0o755, true);
            }
            file_put_contents($target, $contents);
        }
    }
}
