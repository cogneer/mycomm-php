<?php

declare(strict_types=1);

namespace MyComm;

/**
 * Default of an optional argument whose value may also be `null`.
 *
 * Leaving the argument out sends nothing, so the field keeps its value; passing `null` sends `null`, which
 * clears the field.
 */
enum Omit
{
    case Value;
}
