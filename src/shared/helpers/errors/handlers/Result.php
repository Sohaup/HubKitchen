<?php

namespace PostApi\shared\helpers\errors\handlers;

class Result
{
    private function __construct(private bool $isSuccess , private ?mixed $value = null, private ?string $error = "" ) {}

    public static function success(mixed $value) : Result {
        return new self(isSuccess:true , value:$value);
    }

    public static function failture(string $error) : Result {
        return new self(isSuccess:false , error:$error);
    }
}
