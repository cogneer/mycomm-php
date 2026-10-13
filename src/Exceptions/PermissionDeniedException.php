<?php

declare(strict_types=1);

namespace MyComm\Exceptions;

/**
 * 403: the key may not do this (ability, scope, IP allow-list, key kind) or the account is suspended.
 */
final class PermissionDeniedException extends ApiException {}
