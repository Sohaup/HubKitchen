<?php 
namespace PostApi\shared\helpers\fecade;
require_once __DIR__ . "/../utilities/viewError.php";

class ViewError {
    public static function viewProplem(string $type, string $title, string $status, string $detail, int $statusCode) {
        return viewError($type , $title , $status , $detail , $statusCode);
    }
}