<?php

namespace PostApi\shared\helpers\fecade;

use Error;
use Exception;

require_once __DIR__ . "/../utilities/storeFile.php";
require_once __DIR__ . "/../utilities/deleteFile.php";

class Files
{
    public static function storeFile(string $fileName)
    {
        try {
            return storeFile($fileName);
        } catch (Exception $err) {
            throw new Error($err->getMessage());
        }
    }

    public static function deleteFile(string $filePath)
    {
        try {
            return deleteFile($filePath);
        } catch (Exception $err) {
            throw new Error($err->getMessage());
        }
    }

    public static function writeJson(string $filename, array $data): bool
    {
        try {
            $projectSrc = dirname(__DIR__, 5);
            $fullPath = $projectSrc . "/public/uploads/rate_limits/" . $filename;

            $jsonContent = json_encode($data);
            return file_put_contents($fullPath, $jsonContent) !== false;
        } catch (Exception $err) {
            throw new Error($err->getMessage());
        }
    }


    public static function readJson(string $filename): ?array
    {
        try {
            $projectSrc = dirname(__DIR__, 5);
            $fullPath = $projectSrc . "/public/uploads/rate_limits/" . $filename;

            if (!file_exists($fullPath)) {
                return null;
            }

            return json_decode(file_get_contents($fullPath), true);
        } catch (Exception $err) {
            throw new Error($err->getMessage());
        }
    }
}
