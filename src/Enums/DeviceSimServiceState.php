<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Enums;

use DateTimeImmutable;
use DateTimeInterface;
use Generator;
use MyComm\Exceptions\ApiException;
use MyComm\Exceptions\ConnectionException;
use MyComm\FileUpload;
use MyComm\Http\Connection;
use MyComm\Http\Params;
use MyComm\Input;
use MyComm\Inputs;
use MyComm\Model;
use MyComm\Models;
use MyComm\Omit;
use MyComm\Page;
use MyComm\RequestOptions;

/**
 * The API may add values: one this SDK version does not know arrives as a plain string.
 */
enum DeviceSimServiceState: string
{
    case InService = 'in_service';
    case OutOfService = 'out_of_service';
    case EmergencyOnly = 'emergency_only';
    case PowerOff = 'power_off';
}
