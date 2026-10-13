<?php

declare(strict_types=1);

namespace MyComm\Http;

use BackedEnum;
use DateTimeInterface;
use DateTimeZone;
use JsonSerializable;
use MyComm\FileUpload;
use MyComm\Input;
use MyComm\Omit;
use stdClass;

/**
 * Turns the arguments of a resource method into what goes on the wire.
 *
 * @internal
 */
final class Params
{
    /**
     * A JSON body: omitted arguments dropped, enums, dates and nested inputs converted.
     *
     * @param  array<string, mixed>  $fields
     * @return array<string, mixed>
     */
    public static function body(array $fields): array
    {
        $body = [];
        foreach ($fields as $name => $value) {
            if ($value !== Omit::Value) {
                $body[$name] = self::value($value);
            }
        }

        return $body;
    }

    /**
     * Query parameters: nulls dropped, booleans as `true`/`false`, dates as RFC 3339 UTC.
     *
     * @param  array<string, mixed>  $params
     * @return array<string, string>
     */
    public static function query(array $params): array
    {
        $query = [];
        foreach ($params as $name => $value) {
            if ($value === null || $value === Omit::Value) {
                continue;
            }
            $value = self::value($value);
            $query[$name] = match (true) {
                is_bool($value) => $value ? 'true' : 'false',
                is_array($value) => implode(',', array_map(static fn (mixed $item): string => self::scalar($item), $value)),
                default => self::scalar($value),
            };
        }

        return $query;
    }

    /**
     * The fields of a multipart body: omitted ones dropped, arrays sent as JSON text.
     *
     * @param  array<string, mixed>  $fields
     * @return array<string, string|FileUpload>
     */
    public static function multipart(array $fields): array
    {
        $parts = [];
        foreach ($fields as $name => $value) {
            if ($value === null || $value === Omit::Value) {
                continue;
            }
            if ($value instanceof FileUpload) {
                $parts[$name] = $value;

                continue;
            }
            $value = self::value($value);
            $parts[$name] = match (true) {
                is_bool($value) => $value ? 'true' : 'false',
                is_array($value), is_object($value) => (string) json_encode($value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
                default => self::scalar($value),
            };
        }

        return $parts;
    }

    /**
     * A JSON object value: an empty PHP array must be sent as `{}`, never as `[]`.
     */
    public static function object(mixed $value): mixed
    {
        if ($value instanceof Input) {
            $value = $value->toArray();
        }

        return $value === [] ? new stdClass : $value;
    }

    /**
     * A file argument: a `FileUpload`, or the path of a file to read.
     */
    public static function file(FileUpload|string $file): FileUpload
    {
        return is_string($file) ? FileUpload::fromPath($file) : $file;
    }

    public static function date(DateTimeInterface $date): string
    {
        $utc = \DateTimeImmutable::createFromInterface($date)->setTimezone(new DateTimeZone('UTC'));

        return $utc->format((int) $utc->format('u') === 0 ? 'Y-m-d\TH:i:s\Z' : 'Y-m-d\TH:i:s.u\Z');
    }

    private static function value(mixed $value): mixed
    {
        return match (true) {
            $value instanceof BackedEnum => $value->value,
            $value instanceof DateTimeInterface => self::date($value),
            $value instanceof Input => self::object(self::value($value->toArray())),
            $value instanceof JsonSerializable => self::value($value->jsonSerialize()),
            is_array($value) => array_map(static fn (mixed $item): mixed => self::value($item), $value),
            default => $value,
        };
    }

    private static function scalar(mixed $value): string
    {
        return is_scalar($value) || $value instanceof \Stringable ? (string) $value : '';
    }
}
