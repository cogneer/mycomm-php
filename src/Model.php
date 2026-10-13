<?php

declare(strict_types=1);

namespace MyComm;

use BackedEnum;
use DateTimeImmutable;
use JsonSerializable;

/**
 * Base of every object the API returns. The typed properties cover the fields this SDK version knows;
 * `raw()` holds the whole payload, including fields added to the API since.
 */
abstract class Model implements JsonSerializable
{
    /** @var array<string, mixed> */
    private readonly array $raw;

    private readonly ?ResponseMeta $responseMeta;

    /**
     * @param  array<string, mixed>  $data
     */
    final public function __construct(array $data, ?ResponseMeta $meta = null)
    {
        $this->raw = $data;
        $this->responseMeta = $meta;
        $this->hydrate($data);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data, ?ResponseMeta $meta = null): static
    {
        return new static($data, $meta);
    }

    /**
     * The payload as the API sent it.
     *
     * @return array<string, mixed>
     */
    public function raw(): array
    {
        return $this->raw;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return $this->raw;
    }

    /**
     * Request id, rate-limit numbers and replay flag of the call that returned this object; null for an
     * object nested in another one or built by hand.
     */
    public function meta(): ?ResponseMeta
    {
        return $this->responseMeta;
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return $this->raw;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    abstract protected function hydrate(array $data): void;

    protected static function dateTime(string $value): DateTimeImmutable
    {
        return new DateTimeImmutable($value);
    }

    /**
     * The enum case of a value, or the value itself when this SDK version does not know it yet.
     *
     * @template T of BackedEnum
     *
     * @param  class-string<T>  $enum
     * @return T|string
     */
    protected static function enum(string $enum, string $value): BackedEnum|string
    {
        return $enum::tryFrom($value) ?? $value;
    }

    /**
     * Picks the model of a value that may be one of several objects: the first whose required fields are
     * all present. Falls back to the raw array.
     *
     * @template T of Model
     *
     * @param  array<string, mixed>  $data
     * @param  array<class-string<T>, list<string>>  $candidates  Model class => fields it always has.
     * @return T|array<string, mixed>
     */
    protected static function oneOf(array $data, array $candidates): Model|array
    {
        foreach ($candidates as $class => $required) {
            foreach ($required as $field) {
                if (! isset($data[$field])) {
                    continue 2;
                }
            }

            return $class::fromArray($data);
        }

        return $data;
    }
}
