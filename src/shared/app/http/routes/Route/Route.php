<?php

namespace PostApi\shared\app\http\routes\Route;
use Closure;
use PostApi\shared\app\http\middlewares\MiddleareCollection;
use PostApi\shared\app\http\middlewares\Middleware;
use PostApi\shared\app\http\types\HttpMethodsType;

class Route
{
    public MiddleareCollection $middleares;
    public function __construct(public string $path,  public HttpMethodsType $httpMethod , public string $controller , public string $method  ) {
        $this->middleares = new MiddleareCollection();
    }
    public function addMiddleware(Middleware $middleare) {
        $this->middleares->addMiddleware($middleare);
        return $this;
    }
}
