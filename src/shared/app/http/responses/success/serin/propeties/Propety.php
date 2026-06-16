<?php

namespace PostApi\shared\app\http\responses\success\serin\propeties;

class Propety
{
    public function __construct(public string $name, public mixed $value) {}
    
    public function addArrayValue(array $values) {
        $this->value = $values;
    }   
}
