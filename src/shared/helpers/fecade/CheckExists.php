<?php
namespace PostApi\shared\helpers\fecade;
require_once __DIR__ . "/../utilities/checkExists.php";

class CheckExists {
    public static function execute( array $values , string $message) {
        return checkExists($values , $message);
    }
}
