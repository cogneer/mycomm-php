<?php

declare(strict_types=1);

namespace MyComm\Generator;

use RuntimeException;

/**
 * Maps OpenAPI schemas to PHP types and collects the models, enums and inputs those types need.
 *
 * Class names in a `TypeInfo` are written relative to `MyComm\`: `Models\Message`, `Enums\ChannelStatus`,
 * `Inputs\SendBatchItem`. Every generated file imports those three namespaces.
 */
final class Types
{
    /** @var array<string, array{properties: array<string, array{type: TypeInfo, required: bool, description: ?string}>, description: ?string, required: list<string>}> */
    public array $models = [];

    /** @var array<string, array{values: list<string>, description: ?string}> */
    public array $enums = [];

    /** @var array<string, array{fields: array<string, array{type: TypeInfo, required: bool, description: ?string}>, description: ?string}> */
    public array $inputs = [];

    /** @var array<string, string> */
    private array $sources = [];

    /** Above zero while an inline request object is described: its enums stay plain strings. */
    private int $inShape = 0;

    /** @var array<string, true> Corrections of `$nullable` that were applied. */
    public array $corrected = [];

    /**
     * @param  array<string, string>  $enumNames  Schema path (`Channel.type`) => enum class name.
     * @param  array<string, string>  $modelNames  Derived model name => the name it gets instead.
     * @param  array<string, string>  $nullable  Schema path of a field the API returns as null although the spec says it never is => why.
     * @param  array<string, string>  $skipFields  Schema path of a request field the SDK does not offer => why.
     * @param  list<string>  $plainEnums  Enums that stay plain strings in the SDK (the value set is open, or an event class already says it).
     */
    public function __construct(
        private readonly array $enumNames = [],
        private readonly array $modelNames = [],
        private readonly array $nullable = [],
        private readonly array $plainEnums = [],
        private readonly array $skipFields = [],
    ) {}

    /**
     * The type of a value the API returns.
     *
     * @param  array<string, mixed>  $schema
     * @param  string  $context  Class name an inline object or enum at this position gets.
     * @param  string  $path  Position in the spec, for example `Channel.type`.
     */
    public function output(Spec $spec, array $schema, string $context, string $path): TypeInfo
    {
        if (($ref = $spec->refName($schema)) !== null) {
            $target = $spec->schema($ref);
            if ($spec->isObject($target)) {
                // A named object can itself allow null (`type: [object, 'null']`).
                return TypeInfo::model($this->model($spec, $ref, $target), self::typeOf($target)[1]);
            }

            return $this->output($spec, $target, $ref, $ref);
        }

        if (isset($schema['allOf'])) {
            if (($alias = $spec->alias($schema)) !== null) {
                return $this->output($spec, $alias, $context, $path);
            }

            return TypeInfo::model($this->model($spec, $context, $schema));
        }

        if (isset($schema['oneOf']) || isset($schema['anyOf'])) {
            return $this->outputUnion($spec, $schema['oneOf'] ?? $schema['anyOf'], $context, $path);
        }

        [$type, $nullable] = self::typeOf($schema);

        if (isset($schema['enum'])) {
            return $this->enumType($schema, $context, $path, $nullable, output: true);
        }
        if (array_key_exists('const', $schema)) {
            return new TypeInfo(self::scalarOf($schema['const']), self::scalarOf($schema['const']), $nullable);
        }
        if (is_array($type)) {
            $union = self::union(array_map(static fn (string $name): string => $name === 'number' ? 'int|float' : self::scalar($name), $type));

            return new TypeInfo($union, $union, $nullable);
        }

        if ($type === 'array') {
            $item = $this->output($spec, $schema['items'] ?? [], Names::singular($context), $path.'[]');
            $convert = $item->convert === null ? null
                : static fn (string $expr): string => 'array_map(static fn ('.$item->raw.' $item): '.$item->declaration().' => '.($item->convert)('$item').', array_values('.$expr.'))';

            return new TypeInfo('array', 'list<'.$item->docType().'>', $nullable, $convert, 'array');
        }

        if ($type === 'object' || isset($schema['properties'])) {
            if (! empty($schema['properties'])) {
                return TypeInfo::model($this->model($spec, $context, $schema), $nullable);
            }
            $values = $schema['additionalProperties'] ?? null;
            if (is_array($values) && $values !== []) {
                $value = $this->output($spec, $values, Names::singular($context), $path.'{}');
                $convert = $value->convert === null ? null
                    : static fn (string $expr): string => 'array_map(static fn ('.$value->raw.' $item): '.$value->declaration().' => '.($value->convert)('$item').', '.$expr.')';

                return new TypeInfo('array', 'array<string, '.$value->docType().'>', $nullable, $convert, 'array');
            }

            return new TypeInfo('array', 'array<string, mixed>', $nullable, null, 'array');
        }

        if ($type === 'string' && ($schema['format'] ?? null) === 'date-time') {
            return new TypeInfo('DateTimeImmutable', 'DateTimeImmutable', $nullable, static fn (string $expr): string => 'self::dateTime('.$expr.')', 'string');
        }

        return match ($type) {
            'string', 'integer', 'number', 'boolean' => new TypeInfo(self::scalar($type), self::scalar($type), $nullable, null, self::scalar($type)),
            'null' => new TypeInfo('null', 'null'),
            null => new TypeInfo('mixed', 'mixed'),
            default => throw new RuntimeException("Unsupported type {$type} at {$path}."),
        };
    }

    /**
     * The type of a value the caller sends.
     *
     * @param  array<string, mixed>  $schema
     */
    public function input(Spec $spec, array $schema, string $context, string $path): TypeInfo
    {
        if (($ref = $spec->refName($schema)) !== null) {
            $target = $spec->schema($ref);
            if ($spec->isObject($target)) {
                $name = $this->inputObject($spec, $ref, $target);

                return new TypeInfo('Inputs\\'.$name.'|array', 'Inputs\\'.$name.'|array<string, mixed>', self::typeOf($target)[1], object: true);
            }

            return $this->input($spec, $target, $ref, $ref);
        }

        if (isset($schema['allOf'])) {
            if (($alias = $spec->alias($schema)) !== null) {
                return $this->input($spec, $alias, $context, $path);
            }

            return new TypeInfo('array', $this->shape($spec, $schema), object: true);
        }

        if (isset($schema['oneOf']) || isset($schema['anyOf'])) {
            $variants = $schema['oneOf'] ?? $schema['anyOf'];
            $rest = array_values(array_filter($variants, static fn (array $variant): bool => ! self::isNull($variant)));
            $nullable = count($rest) < count($variants);
            if (count($rest) === 1) {
                $type = $this->input($spec, $rest[0], $context, $path);
                $type->nullable = $type->nullable || $nullable;

                return $type;
            }
            $types = array_map(fn (array $variant): TypeInfo => $this->input($spec, $variant, $context, $path), $rest);

            return new TypeInfo(self::union(array_column($types, 'php')), self::union(array_column($types, 'doc')), $nullable);
        }

        [$type, $nullable] = self::typeOf($schema);

        if (isset($schema['enum'])) {
            return $this->enumType($schema, $context, $path, $nullable, output: false);
        }
        if (is_array($type)) {
            $union = self::union(array_map(static fn (string $name): string => $name === 'number' ? 'int|float' : self::scalar($name), $type));

            return new TypeInfo($union, $union, $nullable);
        }

        if ($type === 'array') {
            $item = $this->input($spec, $schema['items'] ?? [], Names::singular($context), $path.'[]');

            return new TypeInfo('array', 'list<'.$item->docType().'>', $nullable);
        }

        if ($type === 'object' || isset($schema['properties'])) {
            return new TypeInfo('array', $this->shape($spec, $schema), $nullable, object: true);
        }

        if ($type === 'string' && ($schema['format'] ?? null) === 'date-time') {
            return new TypeInfo('DateTimeInterface|string', 'DateTimeInterface|string', $nullable);
        }
        if ($type === 'string' && ($schema['format'] ?? null) === 'binary') {
            return new TypeInfo('FileUpload|string', 'FileUpload|string', $nullable, file: true);
        }

        return match ($type) {
            'string', 'integer', 'boolean' => new TypeInfo(self::scalar($type), self::scalar($type), $nullable),
            'number' => new TypeInfo('int|float', 'int|float', $nullable),
            'null' => new TypeInfo('null', 'null'),
            null => new TypeInfo('mixed', 'mixed'),
            default => throw new RuntimeException("Unsupported input type {$type} at {$path}."),
        };
    }

    /**
     * The fields of a request body (or of a nested input object), in declaration order.
     *
     * @param  array<string, mixed>  $schema
     * @param  string  $context  Prefix of the class names nested enums get.
     * @param  string  $path  Name of the schema in the spec, for the enum name overrides.
     * @return array<string, array{type: TypeInfo, required: bool, description: ?string}>
     */
    public function fields(Spec $spec, array $schema, string $context, string $path): array
    {
        $flat = $spec->flatten($schema);
        $fields = [];
        foreach ($flat['properties'] as $name => $property) {
            if (isset($this->skipFields[$path.'.'.$name])) {
                continue;
            }
            $fields[$name] = [
                'type' => $this->input($spec, $property, $context.Names::pascal($name), $path.'.'.$name),
                'required' => in_array($name, $flat['required'], true),
                'description' => $property['description'] ?? null,
                'constraints' => $this->constraints($spec, $property),
            ];
        }

        return $fields;
    }

    /**
     * The limits a request value must keep, from its schema: what `Validation\Guard` checks before a call is sent.
     *
     * @param  array<string, mixed>  $property
     * @return array<string, mixed>
     */
    public function constraints(Spec $spec, array $property): array
    {
        $schema = $spec->resolve($property);
        if (($alias = $spec->alias($schema)) !== null) {
            $schema = $spec->resolve($alias);
        }
        foreach (['oneOf', 'anyOf'] as $keyword) {
            if (isset($schema[$keyword])) {
                $branches = array_values(array_filter($schema[$keyword], static fn (array $branch): bool => ! self::isNull($branch)));
                if (count($branches) === 1) {
                    $schema = $spec->resolve($branches[0]);
                }
            }
        }

        $found = [];
        if (isset($schema['enum'])) {
            $values = array_values(array_filter($schema['enum'], static fn (mixed $value): bool => is_string($value)));
            if ($values !== []) {
                $found['enum'] = $values;
            }
        }
        foreach (['minimum' => 'min', 'maximum' => 'max', 'minLength' => 'minLength', 'maxLength' => 'maxLength', 'pattern' => 'pattern',
            'minItems' => 'minItems', 'maxItems' => 'maxItems', 'maxProperties' => 'maxProperties', 'x-max-json-bytes' => 'maxBytes'] as $keyword => $name) {
            if (isset($schema[$keyword])) {
                $found[$name] = $schema[$keyword];
            }
        }
        if (($schema['format'] ?? null) === 'uuid' || (($schema['type'] ?? null) === 'array' && ($spec->resolve($schema['items'] ?? [])['format'] ?? null) === 'uuid')) {
            $found['uuid'] = true;
        }
        $extra = $schema['additionalProperties'] ?? null;
        if (is_array($extra) && is_array($extra['type'] ?? null) && array_diff($extra['type'], ['string', 'number', 'integer', 'boolean', 'null']) === []) {
            $found['scalarValues'] = true;
        }

        return $found;
    }

    /**
     * The rules between the fields of a request body, from the JSON Schema keywords of its schema.
     *
     * @param  array<string, mixed>  $schema
     * @return list<array<int, mixed>>
     */
    public function rules(Spec $spec, array $schema): array
    {
        $rules = [];
        $this->collectRules($spec, $schema, $rules);

        return $rules;
    }

    /**
     * @param  array<string, mixed>  $schema
     * @param  list<array<int, mixed>>  $rules
     */
    private function collectRules(Spec $spec, array $schema, array &$rules): void
    {
        $schema = $spec->resolve($schema);
        foreach ($schema['allOf'] ?? [] as $member) {
            $this->collectRules($spec, $member, $rules);
        }
        foreach (['oneOf' => 'exactlyOne', 'anyOf' => 'atLeastOne'] as $keyword => $rule) {
            $names = [];
            foreach ($schema[$keyword] ?? [] as $branch) {
                if (isset($branch['required']) && count($branch['required']) === 1 && array_keys($branch) === ['required']) {
                    $names[] = $branch['required'][0];
                } else {
                    $names = [];
                    break;
                }
            }
            if (count($names) > 1) {
                $rules[] = [$rule, $names];
            }
        }
        foreach ($schema['dependentRequired'] ?? [] as $field => $needs) {
            foreach ($needs as $need) {
                $rules[] = ['requires', (string) $field, (string) $need];
            }
        }
        if (isset($schema['if'], $schema['then'])) {
            foreach ($schema['if']['properties'] ?? [] as $when => $condition) {
                if (array_key_exists('const', $condition)) {
                    foreach ($schema['then']['required'] ?? [] as $field) {
                        $rules[] = ['requiredWhen', (string) $field, (string) $when, $condition['const']];
                    }
                }
            }
        }
    }

    /**
     * Registers the model of an object schema and returns its class name.
     *
     * @param  array<string, mixed>  $schema
     */
    private function model(Spec $spec, string $name, array $schema): string
    {
        $name = $this->modelNames[$name] ?? $name;
        $source = (string) json_encode(self::withoutAnnotations($schema));
        if (isset($this->sources['model:'.$name])) {
            if ($this->sources['model:'.$name] !== $source) {
                throw new RuntimeException("Two different schemas want the model name {$name}.");
            }

            return $name;
        }
        $this->sources['model:'.$name] = $source;

        $flat = $spec->flatten($schema);
        // Registered before its properties are resolved, so a schema that refers to itself terminates.
        $this->models[$name] = ['properties' => [], 'description' => $flat['description'], 'required' => $flat['required']];

        foreach ($flat['properties'] as $property => $definition) {
            $owner = $flat['origins'][$property] ?? $name;
            $type = $this->output($spec, $definition, $owner.Names::pascal($property), $owner.'.'.$property);
            $required = in_array($property, $flat['required'], true);

            if (isset($this->nullable[$owner.'.'.$property])) {
                if ($type->nullable || ! $required) {
                    throw new RuntimeException("The spec now allows null for {$owner}.{$property}: remove its correction from generator/config.php.");
                }
                $type->nullable = true;
                $this->corrected[$owner.'.'.$property] = true;
            }

            $this->models[$name]['properties'][$property] = [
                'type' => $type,
                'required' => $required,
                'description' => $definition['description'] ?? null,
            ];
        }

        return $name;
    }

    /**
     * A schema without the keys that do not change its shape, so two copies that differ only in their
     * wording count as the same.
     */
    private static function withoutAnnotations(mixed $schema): mixed
    {
        if (! is_array($schema)) {
            return $schema;
        }
        if (! array_is_list($schema)) {
            unset($schema['description'], $schema['examples'], $schema['title']);
        }

        return array_map(self::withoutAnnotations(...), $schema);
    }

    /**
     * @param  array<string, mixed>  $schema
     */
    private function inputObject(Spec $spec, string $schemaName, array $schema): string
    {
        $name = (string) preg_replace('/Request$/', '', $schemaName);
        $source = (string) json_encode($schema);
        if (isset($this->sources['input:'.$name])) {
            if ($this->sources['input:'.$name] !== $source) {
                throw new RuntimeException("Two different schemas want the input name {$name}.");
            }

            return $name;
        }
        $this->sources['input:'.$name] = $source;
        $this->inputs[$name] = ['fields' => [], 'description' => $spec->flatten($schema)['description']];
        $this->inputs[$name]['fields'] = $this->fields($spec, $schema, $name, $schemaName);

        return $name;
    }

    /**
     * @param  list<array<string, mixed>>  $variants
     */
    private function outputUnion(Spec $spec, array $variants, string $context, string $path): TypeInfo
    {
        $rest = array_values(array_filter($variants, static fn (array $variant): bool => ! self::isNull($variant)));
        $nullable = count($rest) < count($variants);

        if ($rest === []) {
            return new TypeInfo('null', 'null');
        }
        if (count($rest) === 1) {
            $type = $this->output($spec, $rest[0], $context, $path);
            $type->nullable = $type->nullable || $nullable;

            return $type;
        }

        $types = array_map(fn (array $variant): TypeInfo => $this->output($spec, $variant, $context, $path), $rest);
        $models = array_values(array_filter($types, static fn (TypeInfo $type): bool => $type->model !== null));

        if ($models === []) {
            // Scalars and enums: an enum inside a union stays a plain string.
            $php = self::union(array_map(static fn (TypeInfo $type): string => $type->enum !== null ? 'string' : $type->php, $types));

            return new TypeInfo($php, $php, $nullable);
        }
        if (count($models) !== count($types)) {
            return new TypeInfo('mixed', 'mixed');
        }

        // Several objects without a discriminator: the first whose always-present fields are all there.
        $candidates = [];
        foreach ($models as $type) {
            $always = [];
            foreach ($this->models[(string) $type->model]['properties'] as $property => $definition) {
                if ($definition['required'] && ! $definition['type']->nullable && $definition['type']->php !== 'null' && $definition['type']->php !== 'mixed') {
                    $always[] = var_export($property, true);
                }
            }
            $candidates[] = $type->php.'::class => ['.implode(', ', $always).']';
        }
        $php = self::union([...array_column($models, 'php'), 'array']);
        $doc = self::union([...array_column($models, 'php'), 'array<string, mixed>']);

        return new TypeInfo($php, $doc, $nullable, static fn (string $expr): string => 'self::oneOf('.$expr.', ['.implode(', ', $candidates).'])', 'array');
    }

    /**
     * @param  array<string, mixed>  $schema
     */
    private function enumType(array $schema, string $context, string $path, bool $nullable, bool $output): TypeInfo
    {
        $values = array_values(array_filter($schema['enum'], static fn (mixed $value): bool => $value !== null));
        $nullable = $nullable || count($values) < count($schema['enum']);

        if ($values === []) {
            return new TypeInfo('null', 'null');
        }
        if (array_filter($values, is_bool(...)) === $values) {
            return new TypeInfo('bool', 'bool', $nullable, null, 'bool');
        }
        if (array_filter($values, is_string(...)) !== $values) {
            // A list of allowed numbers: the number type says enough.
            $scalar = self::union(array_map(self::scalarOf(...), $values));

            return new TypeInfo($scalar, $scalar, $nullable, null, $scalar);
        }
        if (count($values) < 2 || $this->inShape > 0) {
            // A single allowed value is a constant, not a choice worth an enum class.
            return new TypeInfo('string', 'string', $nullable, null, 'string');
        }

        $name = $this->enumNames[$path] ?? $context;
        if (in_array($name, $this->plainEnums, true)) {
            return new TypeInfo('string', 'string', $nullable, null, 'string');
        }
        $known = $this->enums[$name]['values'] ?? null;
        if ($known !== null && (array_diff($known, $values) !== [] || array_diff($values, $known) !== [])) {
            throw new RuntimeException("Enum {$name} at {$path} has other values than its earlier definition.");
        }
        $this->enums[$name] ??= ['values' => $values, 'description' => $schema['description'] ?? null];

        $php = 'Enums\\'.$name.'|string';

        return new TypeInfo(
            $php,
            $php,
            $nullable,
            $output ? static fn (string $expr): string => 'self::enum(Enums\\'.$name.'::class, '.$expr.')' : null,
            'string',
            enum: $name,
        );
    }

    /**
     * The phpdoc array shape of an inline request object.
     *
     * @param  array<string, mixed>  $schema
     */
    private function shape(Spec $spec, array $schema): string
    {
        $flat = $spec->flatten($schema);
        if ($flat['properties'] === []) {
            return 'array<string, mixed>';
        }

        $parts = [];
        $this->inShape++;
        foreach ($flat['properties'] as $name => $property) {
            $type = $this->input($spec, $property, 'Inline', 'inline');
            $doc = str_contains($type->doc, 'array{') ? 'array<string, mixed>' : $type->docType();
            $parts[] = $name.(in_array($name, $flat['required'], true) ? '' : '?').': '.$doc;
        }
        $this->inShape--;

        return 'array{'.implode(', ', $parts).'}';
    }

    /**
     * The `type` of a schema without `null`, and whether `null` was among them.
     *
     * @param  array<string, mixed>  $schema
     * @return array{string|list<string>|null, bool}
     */
    private static function typeOf(array $schema): array
    {
        $type = $schema['type'] ?? null;
        if (! is_array($type)) {
            return [$type, false];
        }

        $rest = array_values(array_filter($type, static fn (string $name): bool => $name !== 'null'));
        $nullable = count($rest) < count($type);

        return match (count($rest)) {
            0 => ['null', false],
            1 => [$rest[0], $nullable],
            default => [$rest, $nullable],
        };
    }

    /**
     * @param  array<string, mixed>  $schema
     */
    private static function isNull(array $schema): bool
    {
        $type = $schema['type'] ?? null;

        return $type === 'null' || $type === ['null'] || ($schema['enum'] ?? null) === [null];
    }

    private static function scalar(string $type): string
    {
        return match ($type) {
            'string' => 'string',
            'integer' => 'int',
            'number' => 'float',
            'boolean' => 'bool',
            'object', 'array' => 'array',
            default => throw new RuntimeException("Unsupported scalar type {$type}."),
        };
    }

    private static function scalarOf(mixed $value): string
    {
        return match (true) {
            is_string($value) => 'string',
            is_int($value) => 'int',
            is_float($value) => 'float',
            is_bool($value) => 'bool',
            default => 'mixed',
        };
    }

    /**
     * @param  list<string>  $types
     */
    private static function union(array $types): string
    {
        $parts = [];
        foreach ($types as $type) {
            foreach (explode('|', $type) as $part) {
                $parts[$part] = true;
            }
        }
        if (isset($parts['mixed'])) {
            return 'mixed';
        }

        return implode('|', array_keys($parts));
    }
}
