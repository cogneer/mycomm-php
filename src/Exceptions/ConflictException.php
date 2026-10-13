<?php

declare(strict_types=1);

namespace MyComm\Exceptions;

/**
 * 409: the request conflicts with the current state, for example `REFERENCE_ID_EXISTS` or `IDEMPOTENCY_KEY_REUSED`.
 */
final class ConflictException extends ApiException {}
