<?php

declare(strict_types=1);

namespace MyComm\Exceptions;

/**
 * 400: the request is malformed, the cursor is invalid or the line context is missing.
 */
class BadRequestException extends ApiException {}
