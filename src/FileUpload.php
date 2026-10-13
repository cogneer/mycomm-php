<?php

declare(strict_types=1);

namespace MyComm;

use MyComm\Exceptions\MyCommException;

/**
 * A file sent with a multipart request, for example to `media->upload()`.
 */
final class FileUpload
{
    private function __construct(
        public readonly string $contents,
        public readonly string $filename,
        public readonly string $contentType,
    ) {}

    public static function fromPath(string $path, ?string $filename = null, ?string $contentType = null): self
    {
        $contents = is_file($path) && is_readable($path) ? file_get_contents($path) : false;
        if ($contents === false) {
            throw new MyCommException("The file {$path} cannot be read.");
        }

        return new self($contents, $filename ?? basename($path), $contentType ?? self::detect($path));
    }

    public static function fromString(string $contents, string $filename, string $contentType = 'application/octet-stream'): self
    {
        return new self($contents, $filename, $contentType);
    }

    private static function detect(string $path): string
    {
        $type = function_exists('mime_content_type') ? @mime_content_type($path) : false;

        return is_string($type) && $type !== '' ? $type : 'application/octet-stream';
    }
}
