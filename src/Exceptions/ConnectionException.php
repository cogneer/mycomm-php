<?php

declare(strict_types=1);

namespace MyComm\Exceptions;

/**
 * No answer arrived: DNS, TLS, timeout or a dropped connection. The request may or may not have reached
 * the API; repeating a POST with the same `Idempotency-Key` is safe.
 */
final class ConnectionException extends MyCommException {}
