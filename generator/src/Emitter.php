<?php

declare(strict_types=1);

namespace MyComm\Generator;

use RuntimeException;

/**
 * Writes the PHP source of models, enums, inputs, resources and webhook events.
 *
 * The output is formatted by Pint afterwards, which also drops the imports a file does not use: every
 * file gets the same import block.
 */
final class Emitter
{
    private const HEADER = "<?php\n\n// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.\n\ndeclare(strict_types=1);\n\n";

    private const IMPORTS = [
        'DateTimeImmutable',
        'DateTimeInterface',
        'Generator',
        'MyComm\Enums',
        'MyComm\Exceptions\ApiException',
        'MyComm\Exceptions\ConnectionException',
        'MyComm\Exceptions\InvalidArgumentException',
        'MyComm\FileUpload',
        'MyComm\Http\Connection',
        'MyComm\Http\Params',
        'MyComm\Input',
        'MyComm\Inputs',
        'MyComm\Model',
        'MyComm\Models',
        'MyComm\Omit',
        'MyComm\Page',
        'MyComm\RequestOptions',
    ];

    /**
     * @param  array{properties: array<string, array{type: TypeInfo, required: bool, description: ?string}>, description: ?string}  $model
     */
    public function model(string $name, array $model): string
    {
        $properties = '';
        $hydrate = '';
        foreach ($model['properties'] as $property => $definition) {
            self::assertIdentifier($property, "property of {$name}");
            $type = clone $definition['type'];
            $type->nullable = $type->nullable || ! $definition['required'];

            $properties .= self::docblock(Names::inline($definition['description']) === '' ? [] : [Names::inline($definition['description'])], $type->needsDoc() ? ['@var '.$type->docType()] : [], 1);
            $properties .= "    public readonly {$type->declaration()} \${$property};\n\n";
            $hydrate .= "        \$this->{$property} = ".self::hydration($type, "\$data['{$property}']").";\n";
        }

        $code = self::docblock(Names::docLines($model['description']), [], 0)
            ."final class {$name} extends Model\n{\n"
            .$properties
            ."    protected function hydrate(array \$data): void\n    {\n".$hydrate."    }\n}\n";

        return $this->file('MyComm\Models', $code);
    }

    /**
     * @param  array{values: list<string>, description: ?string}  $enum
     */
    public function enum(string $name, array $enum): string
    {
        $cases = '';
        $used = [];
        foreach ($enum['values'] as $value) {
            $case = Names::enumCase($value);
            while (isset($used[$case])) {
                $case .= '_';
            }
            $used[$case] = true;
            $cases .= "    case {$case} = ".var_export($value, true).";\n";
        }

        $doc = Names::docLines($enum['description']);
        if ($doc !== []) {
            $doc[] = '';
        }
        $doc[] = 'The API may add values: one this SDK version does not know arrives as a plain string.';

        return $this->file('MyComm\Enums', self::docblock($doc, [], 0)."enum {$name}: string\n{\n{$cases}}\n");
    }

    /**
     * @param  array{fields: array<string, array{type: TypeInfo, required: bool, description: ?string}>, description: ?string}  $input
     */
    public function input(string $name, array $input): string
    {
        $parameters = $this->parameters([], $input['fields'], [], false);
        $signature = '';
        foreach ($parameters as $parameter) {
            $signature .= "        public readonly {$parameter['declaration']},\n";
        }

        $code = self::docblock(Names::docLines($input['description']), [], 0)
            ."final class {$name} extends Input\n{\n"
            .self::docblock([], self::paramTags($parameters), 1)
            ."    public function __construct(\n{$signature}    ) {}\n\n"
            ."    public function toArray(): array\n    {\n        return Params::body([\n"
            .$this->bodyLines($input['fields'], '$this->')
            ."        ]);\n    }\n}\n";

        return $this->file('MyComm\Inputs', $code);
    }

    /**
     * One resource class: the operations of a node of the tree and its child resources.
     *
     * @param  list<string>  $path  Segments of the resource, for example `['contacts', 'identities']`.
     * @param  list<array<string, mixed>>  $operations
     * @param  list<string>  $children
     */
    public function resource(array $path, array $operations, array $children): string
    {
        $class = Names::pascal((string) end($path));
        $namespace = 'MyComm\Resources'.implode('', array_map(static fn (string $segment): string => '\\'.Names::pascal($segment), array_slice($path, 0, -1)));

        $properties = '';
        $boot = '';
        foreach ($children as $child) {
            $childClass = $class.'\\'.Names::pascal($child);
            $properties .= "    public readonly {$childClass} \${$child};\n\n";
            $boot .= "        \$this->{$child} = new {$childClass}(\$connection);\n";
        }

        $methods = '';
        foreach ($operations as $operation) {
            $methods .= "\n".$this->method($operation);
        }

        $code = "final class {$class}\n{\n".$properties
            ."    public function __construct(private readonly Connection \$connection)\n    {\n{$boot}    }\n"
            .$methods."}\n";

        return $this->file($namespace, $code);
    }

    /**
     * The trait that gives the client its top-level resources.
     *
     * @param  list<string>  $resources
     */
    public function clientResources(array $resources): string
    {
        $properties = '';
        $boot = '';
        foreach ($resources as $resource) {
            $class = Names::pascal($resource);
            $properties .= "    public readonly Resources\\{$class} \${$resource};\n\n";
            $boot .= "        \$this->{$resource} = new Resources\\{$class}(\$connection);\n";
        }

        $code = "/**\n * The top-level resources of the API.\n *\n * @internal\n */\ntrait ProvidesResources\n{\n".$properties
            ."    private function bootResources(Connection \$connection): void\n    {\n{$boot}    }\n}\n";

        return $this->file('MyComm\Resources\Concerns', $code, ['MyComm\Resources']);
    }

    /**
     * The base class of the webhook events: the envelope every event shares.
     *
     * @param  array<string, array{type: TypeInfo, required: bool, description: ?string}>  $envelope  Without `data`.
     */
    public function eventBase(array $envelope): string
    {
        $properties = '';
        $hydrate = '';
        foreach ($envelope as $property => $definition) {
            $type = clone $definition['type'];
            $type->nullable = $type->nullable || ! $definition['required'];
            $properties .= self::docblock(Names::inline($definition['description']) === '' ? [] : [Names::inline($definition['description'])], $type->needsDoc() ? ['@var '.$type->docType()] : [], 1);
            $properties .= "    public readonly {$type->declaration()} \${$property};\n\n";
            $hydrate .= "        \$this->{$property} = ".self::hydration($type, "\$data['{$property}']").";\n";
        }

        $code = "/**\n * A webhook event. `type` names it; `data` (on each event class) carries what happened.\n *\n"
            ." * Delivery is at least once and unordered: de-duplicate on `id` and never move a status backwards.\n */\n"
            ."abstract class Event extends Model\n{\n".$properties
            ."    final protected function hydrate(array \$data): void\n    {\n".$hydrate."        \$this->hydrateData(\$data);\n    }\n\n"
            ."    /**\n     * @param  array<string, mixed>  \$data  The whole event.\n     */\n"
            ."    abstract protected function hydrateData(array \$data): void;\n}\n";

        return $this->file('MyComm\Webhooks', $code);
    }

    public function event(string $class, string $type, ?string $description, TypeInfo $data): string
    {
        $code = self::docblock([...Names::docLines('`'.$type.'`: '.($description ?? ''))], [], 0)
            ."final class {$class} extends Event\n{\n    public const TYPE = ".var_export($type, true).";\n\n"
            .self::docblock([], $data->needsDoc() ? ['@var '.$data->docType()] : [], 1)
            ."    public readonly {$data->declaration()} \$data;\n\n"
            ."    protected function hydrateData(array \$data): void\n    {\n        \$this->data = ".self::hydration($data, "\$data['data']").";\n    }\n}\n";

        return $this->file('MyComm\Webhooks\Events', $code, ['MyComm\Webhooks\Event']);
    }

    /**
     * @param  array<string, string>  $events  Event type => class name.
     */
    public function eventMap(array $events): string
    {
        $lines = '';
        foreach ($events as $type => $class) {
            $lines .= '        '.var_export($type, true)." => Events\\{$class}::class,\n";
        }

        $code = "/**\n * Event type => event class.\n *\n * @internal\n */\nfinal class EventMap\n{\n"
            ."    /** @var array<string, class-string<Event>> */\n    public const EVENTS = [\n{$lines}    ];\n}\n";

        return $this->file('MyComm\Webhooks', $code);
    }

    /**
     * @param  array<string, array{status: int, body: mixed}>  $responses
     * @param  array<string, mixed>  $events
     */
    public function examples(array $responses, array $events): string
    {
        $code = "/**\n * A realistic answer for every operation and a payload for every webhook event, built from the API\n"
            ." * reference. The testing fake answers with these.\n *\n * @internal\n */\nfinal class Examples\n{\n"
            ."    /** @var array<string, array{status: int, body: array<string, mixed>|null}> */\n    public const RESPONSES = ".self::export($responses, 1).";\n\n"
            ."    /** @var array<string, array<string, mixed>> */\n    public const EVENTS = ".self::export($events, 1).";\n}\n";

        return $this->file('MyComm\Testing', $code);
    }

    /**
     * A PHP file that returns a value, for the generated test datasets.
     */
    public function data(mixed $value): string
    {
        return self::HEADER.'return '.self::export($value, 0).";\n";
    }

    /**
     * @param  array<string, mixed>  $operation
     */
    private function method(array $operation): string
    {
        $id = $operation['id'];
        $parameters = $this->parameters($operation['pathParams'], $operation['fields'], $operation['query'], true);
        $names = array_column($parameters, 'name');
        if (count($names) !== count(array_unique($names))) {
            throw new RuntimeException("{$id}: a path, body and query parameter share a name.");
        }

        [$returnType, $returnDoc, $returnCode] = $this->returns($operation, $parameters);
        $guard = $this->guard($operation, $names);
        if ($guard !== '') {
            $returnCode = $guard.$returnCode;
        }

        $signature = '';
        foreach ($parameters as $parameter) {
            $signature .= "        {$parameter['declaration']},\n";
        }

        if (array_intersect($names, ['response', 'next', 'item']) !== []) {
            throw new RuntimeException("{$id}: a parameter is named like a local variable of the generated method.");
        }

        $arguments = '';
        if ($operation['query'] !== []) {
            $arguments .= "            query: Params::query([\n";
            foreach ($operation['query'] as $name => $_) {
                $arguments .= '                '.var_export($name, true)." => \${$name},\n";
            }
            $arguments .= "            ]),\n";
        }
        if ($operation['format'] === 'json' && $operation['fields'] !== []) {
            $arguments .= "            body: Params::body([\n".$this->bodyLines($operation['fields'], '$', 4)."            ]),\n";
        }
        if ($operation['format'] === 'multipart') {
            $arguments .= "            multipart: [\n";
            foreach ($operation['fields'] as $name => $field) {
                $value = match (true) {
                    ! $field['type']->file => "\${$name}",
                    $field['required'] => "Params::file(\${$name})",
                    default => "\${$name} === null ? null : Params::file(\${$name})",
                };
                $arguments .= '                '.var_export($name, true)." => {$value},\n";
            }
            $arguments .= "            ],\n";
        }

        $path = self::pathExpression($operation['path']);
        $call = $arguments === ''
            ? "\$this->connection->request('{$id}', '{$operation['http']}', {$path}, options: \$requestOptions)"
            : "\$this->connection->request(\n            '{$id}',\n            '{$operation['http']}',\n            {$path},\n{$arguments}            options: \$requestOptions,\n        )";

        $doc = [];
        if ($operation['summary'] !== null) {
            $doc[] = rtrim((string) $operation['summary'], '.').'.';
            $doc[] = '';
        }
        array_push($doc, ...Names::docLines($operation['description']));
        $tags = self::paramTags($parameters);
        if ($operation['deprecated']) {
            array_unshift($tags, '@deprecated');
        }
        if ($returnDoc !== null) {
            $tags[] = '@return '.$returnDoc;
        }
        $tags[] = '';
        if ($guard !== '') {
            $tags[] = '@throws InvalidArgumentException when an argument breaks a rule the API documents; nothing is sent (client option `validate`)';
        }
        $tags[] = '@throws ApiException when the API answers with an error';
        $tags[] = '@throws ConnectionException when the API cannot be reached';

        $code = self::docblock($doc, $tags, 1)
            ."    public function {$operation['name']}(\n{$signature}    ): {$returnType} {\n"
            .str_replace('{call}', $call, $returnCode)."    }\n";

        if ($operation['name'] === 'list' && $this->paginated($operation)) {
            $code .= "\n".$this->allMethod($operation, $parameters);
        }

        return $code;
    }

    /**
     * The call that checks the arguments against the limits and rules of the document before the request is sent.
     *
     * @param  array<string, mixed>  $operation
     * @param  list<string>  $arguments  The names of the method's arguments.
     */
    private function guard(array $operation, array $arguments): string
    {
        $constraints = [];
        foreach ([...$operation['fields'], ...$operation['query']] as $name => $field) {
            if (($field['constraints'] ?? []) !== []) {
                $constraints[$name] = $field['constraints'];
            }
        }
        $rules = $operation['rules'] ?? [];
        if ($constraints === [] && $rules === []) {
            return '';
        }

        $names = array_keys($constraints);
        foreach ($rules as $rule) {
            foreach (array_slice($rule, 1) as $part) {
                if ($rule[0] === 'requiredWhen' && $part === $rule[3]) {
                    continue;
                }
                array_push($names, ...(array) $part);
            }
        }
        $names = array_values(array_unique($names));
        $unknown = array_diff($names, $arguments);
        if ($unknown !== []) {
            throw new RuntimeException("{$operation['id']}: a rule or limit names ".implode(', ', $unknown).', which is not an argument of the method.');
        }

        $values = [];
        foreach ($names as $name) {
            $values[$name] = new RawCode('$'.$name);
        }

        return '        $this->connection->guard(
'
            ."            '{$operation['id']}',
"
            .'            '.self::export($values, 3).',
'
            .'            '.self::export($constraints, 3).',
'
            .'            '.self::export($rules, 3).',
'
            .'        );

';
    }

    /**
     * The companion of a paginated `list`: every item of every page.
     *
     * @param  array<string, mixed>  $operation
     * @param  list<array{name: string, declaration: string, doc: ?string, description: ?string}>  $parameters
     */
    private function allMethod(array $operation, array $parameters): string
    {
        $item = (self::page($operation) ?? throw new RuntimeException("{$operation['id']} is not a list."))['item'];
        $parameters = array_values(array_filter($parameters, static fn (array $parameter): bool => $parameter['name'] !== 'cursor'));

        $signature = '';
        $forward = [];
        foreach ($parameters as $parameter) {
            $signature .= "        {$parameter['declaration']},\n";
            $forward[] = "{$parameter['name']}: \${$parameter['name']}";
        }

        $tags = self::paramTags($parameters);
        $tags[] = '@return Generator<int, '.$item->docType().'>';
        $tags[] = '';
        $tags[] = '@throws ApiException when the API answers with an error';
        $tags[] = '@throws ConnectionException when the API cannot be reached';

        return self::docblock(['Every item of `list()`, fetching the following pages as the loop reaches them.', '', '`limit` is the page size, not a cap on the items.'], $tags, 1)
            ."    public function all(\n{$signature}    ): Generator {\n        return \$this->list(".implode(', ', $forward).")->autoPaging();\n    }\n";
    }

    /**
     * The return type, its phpdoc and the code that produces it (`{call}` stands for the request).
     *
     * @param  array<string, mixed>  $operation
     * @param  list<array{name: string, declaration: string, doc: ?string, description: ?string}>  $parameters
     * @return array{string, ?string, string}
     */
    private function returns(array $operation, array $parameters): array
    {
        $responses = $operation['responses'];
        if ($responses === []) {
            // No success answer is documented (an operation that is not enabled yet): hand back the JSON.
            return ['array', 'array<string, mixed>', "        return {call}->json();\n"];
        }

        $page = self::page($operation);
        if ($page !== null) {
            $item = $page['item'];
            $map = $item->convert === null
                ? 'static fn (mixed $item): mixed => $item'
                : 'static fn ('.$item->raw.' $item): '.$item->declaration().' => '.($item->convert)('$item');

            $next = 'null';
            if ($this->paginated($operation)) {
                $forward = [];
                foreach ($parameters as $parameter) {
                    $forward[] = $parameter['name'].': '.($parameter['name'] === 'cursor' ? '$next' : '$'.$parameter['name']);
                }
                $next = 'fn (string $next): Page => $this->'.$operation['name'].'('.implode(', ', $forward).')';
            }

            return ['Page', 'Page<'.$item->docType().'>', "        \$response = {call};\n\n        return Page::fromResponse(\$response, {$map}, {$next});\n"];
        }

        $empty = false;
        /** @var array<int, TypeInfo> $models */
        $models = [];
        foreach ($responses as $status => $response) {
            if ($response['kind'] === 'none') {
                $empty = true;
            } else {
                $models[$status] = $response['type'];
            }
        }

        if ($models === []) {
            return ['void', null, "        {call};\n"];
        }

        $build = static fn (TypeInfo $type): string => $type->model !== null
            ? $type->php.'::fromArray($response->json(), $response->meta())'
            : '$response->json()';
        /** @var array<string, TypeInfo> $distinct */
        $distinct = [];
        foreach ($models as $type) {
            $distinct[$type->php] = $type;
        }

        $code = "        \$response = {call};\n\n";
        if ($empty) {
            $code .= "        if (\$response->body === '') {\n            return null;\n        }\n\n";
        }

        if (count($distinct) === 1) {
            $only = array_values($distinct)[0];
            $type = new TypeInfo($only->php, $only->doc, $empty, $only->convert, $only->raw, model: $only->model);

            return [$type->declaration(), $type->needsDoc() ? $type->docType() : null, $code.'        return '.$build($type).";\n"];
        }

        // Different objects per status, for example a dry run (200) and an accepted message (202).
        $statuses = array_keys($models);
        $last = array_pop($statuses);
        $arms = '';
        foreach ($statuses as $status) {
            $arms .= "            {$status} => ".$build($models[$status]).",\n";
        }
        $arms .= '            default => '.$build($models[$last]).",\n";

        return [
            implode('|', array_keys($distinct)).($empty ? '|null' : ''),
            null,
            $code."        return match (\$response->status) {\n{$arms}        };\n",
        ];
    }

    /**
     * The parameters of a method, required ones first.
     *
     * @param  list<string>  $pathParams
     * @param  array<string, array{type: TypeInfo, required: bool, description: ?string}>  $fields
     * @param  array<string, array{type: TypeInfo, required: bool, description: ?string}>  $query
     * @return list<array{name: string, declaration: string, doc: ?string, description: ?string}>
     */
    private function parameters(array $pathParams, array $fields, array $query, bool $requestOptions): array
    {
        $required = [];
        $optional = [];

        foreach ($pathParams as $name) {
            self::assertIdentifier($name, 'path parameter');
            $required[] = ['name' => $name, 'declaration' => "string \${$name}", 'doc' => null, 'description' => null];
        }

        foreach ($fields as $name => $field) {
            self::assertIdentifier($name, 'request field');
            $type = $field['type'];
            $doc = $type->needsDoc() ? $type->docType() : null;
            if ($field['required']) {
                $required[] = ['name' => $name, 'declaration' => "{$type->declaration()} \${$name}", 'doc' => $doc, 'description' => $field['description']];
            } elseif ($type->php === 'mixed') {
                $optional[] = ['name' => $name, 'declaration' => "mixed \${$name} = Omit::Value", 'doc' => null, 'description' => $field['description']];
            } elseif ($type->nullable) {
                // `null` is a value here (it clears the field), so "not sent" needs its own default.
                $optional[] = ['name' => $name, 'declaration' => "{$type->php}|Omit|null \${$name} = Omit::Value", 'doc' => $doc === null ? null : $type->doc.'|Omit|null', 'description' => $field['description']];
            } else {
                $nullable = clone $type;
                $nullable->nullable = true;
                $optional[] = ['name' => $name, 'declaration' => "{$nullable->declaration()} \${$name} = null", 'doc' => $doc === null ? null : $nullable->docType(), 'description' => $field['description']];
            }
        }

        foreach ($query as $name => $parameter) {
            self::assertIdentifier($name, 'query parameter');
            $type = clone $parameter['type'];
            if ($parameter['required']) {
                $required[] = ['name' => $name, 'declaration' => "{$type->declaration()} \${$name}", 'doc' => $type->needsDoc() ? $type->docType() : null, 'description' => $parameter['description']];
            } else {
                $type->nullable = true;
                $optional[] = ['name' => $name, 'declaration' => "{$type->declaration()} \${$name} = null", 'doc' => $type->needsDoc() ? $type->docType() : null, 'description' => $parameter['description']];
            }
        }

        if ($requestOptions) {
            $optional[] = ['name' => 'requestOptions', 'declaration' => '?RequestOptions $requestOptions = null', 'doc' => null, 'description' => null];
        }

        return [...$required, ...$optional];
    }

    /**
     * The entries of a `Params::body([...])` call.
     *
     * @param  array<string, array{type: TypeInfo, required: bool, description: ?string}>  $fields
     * @param  string  $prefix  `$` for method arguments, `$this->` for the properties of an input.
     */
    private function bodyLines(array $fields, string $prefix, int $indent = 3): string
    {
        $lines = '';
        foreach ($fields as $name => $field) {
            $type = $field['type'];
            $value = $prefix.$name;
            // An optional argument that cannot be null uses null for "not sent".
            if (! $field['required'] && ! $type->nullable && $type->php !== 'mixed') {
                $value .= ' ?? Omit::Value';
            }
            if ($type->object) {
                $value = "Params::object({$value})";
            }
            $lines .= str_repeat('    ', $indent).var_export($name, true)." => {$value},\n";
        }

        return $lines;
    }

    /**
     * @param  list<array{name: string, declaration: string, doc: ?string, description: ?string}>  $parameters
     * @return list<string>
     */
    private static function paramTags(array $parameters): array
    {
        $tags = [];
        foreach ($parameters as $parameter) {
            $description = Names::inline($parameter['description']);
            if ($parameter['doc'] === null && $description === '') {
                continue;
            }
            $type = $parameter['doc'] ?? (string) preg_replace('/ \$.*$/', '', $parameter['declaration']);
            $tags[] = rtrim("@param  {$type}  \${$parameter['name']}  {$description}");
        }

        return $tags;
    }

    /**
     * @param  array<string, mixed>  $operation
     * @return array{kind: string, item: TypeInfo}|null
     */
    private static function page(array $operation): ?array
    {
        foreach ($operation['responses'] as $response) {
            if ($response['kind'] === 'page') {
                return $response;
            }
        }

        return null;
    }

    /**
     * @param  array<string, mixed>  $operation
     */
    private function paginated(array $operation): bool
    {
        return self::page($operation) !== null && isset($operation['query']['cursor']);
    }

    /**
     * The code that reads one field of a payload into its typed property.
     */
    private static function hydration(TypeInfo $type, string $access): string
    {
        if ($type->php === 'null') {
            return 'null';
        }
        if ($type->nullable || $type->php === 'mixed') {
            return $type->convert === null ? "{$access} ?? null" : "isset({$access}) ? ".($type->convert)($access).' : null';
        }

        return $type->convert === null ? $access : ($type->convert)($access);
    }

    /**
     * `/v1/contacts/{id}/identities` => `'/v1/contacts/'.rawurlencode($id).'/identities'`.
     */
    private static function pathExpression(string $path): string
    {
        $parts = preg_split('/\{([^}]+)\}/', $path, -1, PREG_SPLIT_DELIM_CAPTURE) ?: [];
        $expression = [];
        foreach ($parts as $index => $part) {
            if ($index % 2 === 1) {
                $expression[] = "rawurlencode(\${$part})";
            } elseif ($part !== '') {
                $expression[] = var_export($part, true);
            }
        }

        return implode('.', $expression);
    }

    /**
     * @param  list<string>  $lines
     * @param  list<string>  $tags
     */
    private static function docblock(array $lines, array $tags, int $indent): string
    {
        while ($lines !== [] && end($lines) === '') {
            array_pop($lines);
        }
        while ($tags !== [] && $tags[0] === '') {
            array_shift($tags);
        }
        if ($lines === [] && $tags === []) {
            return '';
        }

        $all = $lines;
        if ($lines !== [] && $tags !== []) {
            $all[] = '';
        }
        array_push($all, ...$tags);

        $pad = str_repeat('    ', $indent);
        $doc = "{$pad}/**\n";
        foreach ($all as $line) {
            $doc .= rtrim("{$pad} * {$line}")."\n";
        }

        return $doc."{$pad} */\n";
    }

    /**
     * @param  list<string>  $extraImports
     */
    private function file(string $namespace, string $code, array $extraImports = []): string
    {
        $short = substr($namespace, strlen('MyComm\\'));
        // Inside MyComm\Models a model is named without its namespace, and so on.
        if (in_array($short, ['Models', 'Enums', 'Inputs'], true)) {
            $code = (string) preg_replace('/(?<![A-Za-z0-9_\\\\])'.$short.'\\\\(?=[A-Z])/', '', $code);
        }

        $imports = '';
        foreach ([...self::IMPORTS, ...$extraImports] as $import) {
            if ($import !== $namespace && ! str_starts_with($import, $namespace.'\\') || in_array($import, $extraImports, true)) {
                $imports .= "use {$import};\n";
            }
        }

        return self::HEADER."namespace {$namespace};\n\n{$imports}\n{$code}";
    }

    private static function assertIdentifier(string $name, string $what): void
    {
        if (preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $name) !== 1 || $name === 'this') {
            throw new RuntimeException("The {$what} `{$name}` is not a usable PHP name.");
        }
    }

    private static function export(mixed $value, int $indent): string
    {
        if (! is_array($value)) {
            return match (true) {
                $value instanceof RawCode => $value->code,
                $value === null => 'null',
                is_bool($value) => $value ? 'true' : 'false',
                default => var_export($value, true),
            };
        }
        if ($value === []) {
            return '[]';
        }
        if (array_is_list($value) && array_filter($value, static fn (mixed $item): bool => is_scalar($item) || $item instanceof RawCode) === $value && count($value) <= 8) {
            return '['.implode(', ', array_map(static fn (mixed $item): string => self::export($item, 0), $value)).']';
        }

        $pad = str_repeat('    ', $indent + 1);
        $list = array_is_list($value);
        $code = "[\n";
        foreach ($value as $key => $item) {
            $code .= $pad.($list ? '' : var_export($key, true).' => ').self::export($item, $indent + 1).",\n";
        }

        return $code.str_repeat('    ', $indent).']';
    }
}
