<?php

namespace Crockerio\SearchEngine\Utils;

class FileUtils
{
    public static function createDirectoryIfNotExists($directory)
    {
        if (! file_exists($directory)) {
            mkdir($directory, 0777, true);
        }
    }
}
