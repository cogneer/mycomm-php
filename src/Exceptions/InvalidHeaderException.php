<?php

declare(strict_types=1);

namespace MyComm\Exceptions;

/**
 * 400 `INVALID_HEADER`: a request header has a value the API cannot use. `details['header']` names it, `details['reason']` says why and `details['expected']` what is accepted.
 */
final class InvalidHeaderException extends BadRequestException {}
