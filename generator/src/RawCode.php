<?php

declare(strict_types=1);

namespace MyComm\Generator;

/**
 * A piece of PHP that is written into the generated code as it is, not quoted.
 */
final class RawCode
{
    public function __construct(public readonly string $code) {}
}
