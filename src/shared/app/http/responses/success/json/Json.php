<?php 
namespace PostApi\shared\app\http\responses\success\json;

class Json {
    public static function toJson(mixed $value) {
        header("Content-Type:application/json");
        return json_encode($value);
    }
}