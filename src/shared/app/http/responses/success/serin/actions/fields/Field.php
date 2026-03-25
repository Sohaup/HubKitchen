<?php
namespace PostApi\shared\app\http\responses\success\serin\actions\fields;

class Field {
    public function __construct(public string $name , public string $type , public mixed $value = null)
    {
        
    }
}