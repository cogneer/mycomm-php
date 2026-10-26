<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Enums;

/**
 * The API may add values: one this SDK version does not know arrives as a plain string.
 */
enum ImportStatus: string
{
    case Uploaded = 'uploaded';
    case Mapping = 'mapping';
    case Queued = 'queued';
    case AwaitingDeviceConfirmation = 'awaiting_device_confirmation';
    case Running = 'running';
    case Completed = 'completed';
    case Failed = 'failed';
    case Cancelled = 'cancelled';
}
