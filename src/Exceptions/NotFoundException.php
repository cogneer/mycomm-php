<?php

declare(strict_types=1);

namespace MyComm\Exceptions;

/**
 * 404: no such resource, or it lies outside the scope of the key.
 */
final class NotFoundException extends ApiException {}
