<?php

namespace PostApi\shared\helpers\fecade;
require_once __DIR__ . "/../utilities/storeFile.php";
require_once __DIR__ . "/../utilities/deleteFile.php";

class Files
{
    public static function storeFile(string $fileName)
    {        
        return storeFile($fileName);
    }

    public static function deleteFile(string $filePath)
    {
        return deleteFile($filePath);
    }
}