<?php

declare(strict_types=1);

namespace MyComm\Http;

/**
 * Reads a `Retry-After` header: a number of seconds, or an HTTP date.
 *
 * @internal
 */
final class RetryAfter
{
    public static function seconds(?string $value, ?int $now = null): ?int
    {
        if ($value === null || trim($value) === '') {
            return null;
        }
        if (is_numeric($value)) {
            return max(0, (int) $value);
        }

        $at = strtotime($value);

        return $at === false ? null : max(0, $at - ($now ?? time()));
    }
}
