<?php
namespace App\User\Util;

class FileUtil
{
    public static function getExtension(string $filename): string
    {
        return pathinfo($filename, PATHINFO_EXTENSION);
    }

    public static function sanitizeFilename(string $filename): string
    {
        // Remove special chars, spaces, etc.
        return preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $filename);
    }
}
