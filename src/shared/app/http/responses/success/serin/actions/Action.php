<?php
namespace PostApi\shared\app\http\responses\success\serin\actions;

use PostApi\shared\app\http\responses\success\serin\actions\fields\Fields;
use PostApi\shared\app\http\types\HttpMethodsType;

class Action {
    public array $fields = []; 
    public function __construct(public string $name , public HttpMethodsType $method , public string $href , public string $type ,  Fields $fields )
    {
        $this->fields = $fields->fields;
    }
}