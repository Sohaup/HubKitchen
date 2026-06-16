<?php

use PostApi\shared\app\http\requests\Request;
use PostApi\shared\app\http\responses\success\json\Json;
use PostApi\shared\app\http\responses\success\serin\SerinJson;

function checkCache(SerinJson $entities) {
    
    $request = new Request();
    $headers = $request->headers;
    $jsonEntities =  json_encode($entities);
    $etag = md5($jsonEntities); 
   
    header("Cache-Control: public, max-age=300, must-revalidate");
    header("ETag: \"$etag\"");
    $clientETag = isset($headers['If-None-Match']) ? trim($headers['If-None-Match']) : '';
    
    if ($clientETag == $etag) {
        http_response_code(304);
        exit;
    }

    http_response_code(200);
    return Json::toJson($entities);
}