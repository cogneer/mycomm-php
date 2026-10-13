<?php

declare(strict_types=1);

namespace MyComm\Generator;

use Closure;

/**
 * A PHP type as the generator writes it, without its nullability.
 */
final class TypeInfo
{
    /**
     * @param  string  $php  Native type, for example `string`, `Models\Message` or `Enums\ChannelStatus|string`.
     * @param  string  $doc  phpdoc type, for example `list<Models\Message>`.
     * @param  (Closure(string): string)|null  $convert  Code that turns the raw JSON value into the type; null when the value is used as it is.
     * @param  string  $raw  Native type of the raw JSON value.
     * @param  bool  $object  A JSON object the caller sends: an empty array must be encoded as `{}`.
     * @param  bool  $file  A file of a multipart request.
     */
    public function __construct(
        public string $php,
        public string $doc,
        public bool $nullable = false,
        public ?Closure $convert = null,
        public string $raw = 'mixed',
        public bool $object = false,
        public bool $file = false,
        public ?string $model = null,
        public ?string $enum = null,
    ) {}

    public static function model(string $name, bool $nullable = false): self
    {
        $class = 'Models\\'.$name;

        return new self($class, $class, $nullable, static fn (string $expr): string => $class.'::fromArray('.$expr.')', 'array', model: $name);
    }

    /**
     * The native type with its nullability.
     */
    public function declaration(): string
    {
        if (! $this->nullable || $this->php === 'mixed' || $this->php === 'null') {
            return $this->php;
        }

        return str_contains($this->php, '|') ? $this->php.'|null' : '?'.$this->php;
    }

    public function docType(): string
    {
        return $this->nullable && $this->doc !== 'mixed' && $this->doc !== 'null' ? $this->doc.'|null' : $this->doc;
    }

    /**
     * Whether the phpdoc type says more than the native one.
     */
    public function needsDoc(): bool
    {
        return $this->doc !== $this->php;
    }
}
