<?php
namespace PostApi\shared\helpers\fecade;

use PostApi\shared\app\http\responses\success\serin\SerinJson;

require_once __DIR__ . "/../utilities/checkCache.php";

class Chache {
    public static function checkCache(SerinJson $entities) {
        return checkCache($entities);
    }
}