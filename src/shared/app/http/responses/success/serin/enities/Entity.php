<?php

namespace PostApi\shared\app\http\responses\success\serin\enities;

use PostApi\shared\app\http\responses\success\serin\links\Links;
use PostApi\shared\app\http\responses\success\serin\propeties\Propeties;
use PostApi\shared\app\http\responses\success\serin\propeties\Propety;

class Entity
{   
    public array $propeties;
    public array $links;
    public function __construct(public array $class, public array $rel, Propeties $propeties ,  Links $links) {
        $this->propeties = $propeties->propeties;
        $this->links = $links->links;
    }
}
