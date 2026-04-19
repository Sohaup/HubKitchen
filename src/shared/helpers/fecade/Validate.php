<?php

namespace PostApi\shared\helpers\fecade;

require_once __DIR__ . "/../utilities/validate.php";

class Validate
{
    public static function validateValue(int $type, mixed $value, ?array $options = [], string $message)
    {
        return validateValue($type, $value, $options, $message);
    }
}
