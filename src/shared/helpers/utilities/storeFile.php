<?php

use PostApi\shared\app\http\requests\Request;

function storeFile(string $fileName, array $params = []): string
{
    $uplaodFolder = $fileName . "s";
    $filePath = "";
    $request = new Request();

    if (isset($request->files[$fileName]) && isset($request->files[$fileName]['tmp_name']) && $request->files[$fileName]['error'] === UPLOAD_ERR_OK) {

        $file = $request->files[$fileName];
        $originalName = basename($file['name']);
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $allowedExtensions = ['pdf', 'jpg', 'png', 'jpeg', 'gif'];

        if (!in_array(strtolower($extension), $allowedExtensions)) {
            throw new Exception("an allowble file extension $extension");
        }

        $projectSrc = dirname(__DIR__, 5);
        $uploadDir = $projectSrc . "/public/uploads/$uplaodFolder";

        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0755, true)) {
                throw new Exception("failed to create the folder $uploadDir");
            }
        }

        $filename = uniqid($fileName . '_') . '.' . $extension;
        $destination = $uploadDir . '/' . $filename;


        if (copy($file['tmp_name'], $destination)) {
            unlink($file['tmp_name']);
            $filePath = "/public/uploads/$uplaodFolder/" . $filename;
        } else {
            throw new Exception("failed to move uploaded file to destination: $destination");
        }
    } elseif (isset($params[$fileName]) && is_string($params[$fileName])) {
        $filePath = $params[$fileName];
    } else {
        throw new Exception("there is no file sended with name : $fileName");
    }

    return $filePath;
}
