<?php

namespace PostApi\shared\app\http\routes\Route;
use Closure;
use PostApi\shared\app\http\types\HttpMethodsType;

class Route
{
    public function __construct(public string $path,  public HttpMethodsType $httpMethod , public string $controller , public string $method  ) {}
}
