<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Enums;

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
