<?php

// Generated from the MyComm OpenAPI documents by generator/. Do not edit: run ./dev generate.

declare(strict_types=1);

namespace MyComm\Enums;

/**
 * The kind of content in `content`. `text` and `media` are what the SMS channel sends; the rest belong to
 * other channels. A new value may appear.
 *
 * The API may add values: one this SDK version does not know arrives as a plain string.
 */
enum MessageContentType: string
{
    case Text = 'text';
    case Media = 'media';
    case Interactive = 'interactive';
    case Location = 'location';
    case Reaction = 'reaction';
    case ChannelTemplate = 'channel_template';
    case Unsupported = 'unsupported';
}
