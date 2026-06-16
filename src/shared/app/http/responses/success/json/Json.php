<?php 
namespace PostApi\shared\app\http\responses\success\json;

class Json {
    public static function toJson(mixed $value) {
        return json_encode($value);
    }
}