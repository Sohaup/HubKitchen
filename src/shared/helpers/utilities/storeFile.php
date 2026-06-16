<?php

use PostApi\shared\app\http\requests\Request;

function storeFile(string $fileName)
{
    $uplaodFolder = $fileName . "s";
    $cvPath = "";
    $request = new Request();   
    if (isset($request->files[$fileName]) && isset($request->files[$fileName]['tmp_name']) && $request->files[$fileName]['error'] === UPLOAD_ERR_OK) {
        $file = $request->files[$fileName];
        $originalName = basename($file['name']);
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);       
        if (strtolower($extension) === 'pdf') {
            $projectSrc = dirname(__DIR__, 5);
            $uploadDir = $projectSrc . "/public/uploads/$uplaodFolder";           
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $filename = uniqid($fileName . '_') . '.' . $extension;
            $destination = $uploadDir . '/' . $filename;
           
            if (move_uploaded_file($file['tmp_name'], $destination)) {
                $cvPath = "/public/uploads/$uplaodFolder/" . $filename;              
            }
        }
    } elseif (isset($params[$fileName]) && is_string($params[$fileName])) {
        $cvPath = $params[$fileName];
    }

    return $cvPath;
}
