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

    public static function writeJson(string $filename, array $data): bool
    {
        $projectSrc = dirname(__DIR__, 5);
        $fullPath = $projectSrc . "/public/uploads/rate_limits/" . $filename;

        $jsonContent = json_encode($data);
        return file_put_contents($fullPath, $jsonContent) !== false;
    }

    // دالة جديدة لقراءة كاش الـ Rate Limit
    public static function readJson(string $filename): ?array
    {
        $projectSrc = dirname(__DIR__, 5);
        $fullPath = $projectSrc . "/public/uploads/rate_limits/" . $filename;

        if (!file_exists($fullPath)) {
            return null;
        }

        return json_decode(file_get_contents($fullPath), true);
    }
}
