<?php

declare(strict_types=1);

namespace MyComm\Exceptions;

/**
 * 5xx: the API failed. Repeating a POST with the same `Idempotency-Key` is safe.
 */
final class ServerException extends ApiException {}
