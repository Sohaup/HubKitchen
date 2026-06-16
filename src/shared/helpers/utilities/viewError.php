<?php

use PostApi\shared\app\http\responses\errors\problem\ProblemJson;
use PostApi\shared\app\http\responses\success\json\Json;

function viewError(string $type, string $title, string $status, string $detail, int $statusCode)
{
    http_response_code($statusCode);
    $proplem = new ProblemJson($type, $title, $status, $detail);
    return Json::toJson($proplem);
}
