<?php

function deleteFile(string $filePath)
{
    $projectSrc = dirname(__DIR__, 5);      
    if (unlink($projectSrc.$filePath)) {
        return true;        
    } else {
        return false;        
    }
}
