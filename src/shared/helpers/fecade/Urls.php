<?php 
namespace PostApi\shared\helpers\fecade;
require_once __DIR__ . "/../utilities/transformToUrl.php";
require_once __DIR__ . "/../utilities/transformToRouteUrl.php";

class Urls {
    public static function transformUrl(string $path) {
        return transformToUrl($path);
    }

    public static function transformRouteUrl(string $endPoint) {
       return transformToRouteUrl($endPoint);
    }
}