<?php

declare(strict_types=1);

namespace MyComm\Testing;

use RuntimeException;

/**
 * Thrown by the fake's assertions when PHPUnit is not installed.
 */
final class AssertionFailed extends RuntimeException {}
