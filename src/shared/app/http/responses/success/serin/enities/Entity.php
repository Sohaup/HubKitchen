<?php

namespace PostApi\shared\app\http\responses\success\serin\enities;

class Entity
{
    public function __construct(public array $class, public array $rel, public string $href) {}
}
