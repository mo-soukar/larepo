<?php

namespace Soukar\Larepo\Services;

use Illuminate\Support\Facades\File;

class FilesService
{
    public static function createDirectoryIfNotExists($directory)
    {
        if (!File::exists($directory)) {
            File::makeDirectory(
                $directory,
                0755,
                true,
                true
            );
        }
    }

    public static function checkFile($path)
    {
        return File::exists($path);
    }
}
