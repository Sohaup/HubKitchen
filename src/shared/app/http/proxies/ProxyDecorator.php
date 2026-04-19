<?php

namespace PostApi\shared\app\http\proxies;

use PostApi\shared\app\http\middlewares\Middleware;
use PostApi\shared\app\http\requests\Request;
use PostApi\shared\app\http\routes\Route\RouteCollection;

abstract class ProxyDecorator implements Proxy
{
    public array $middlewares = [];
    public RouteCollection $routes;
    public function __construct(RouteCollection $routes)
    {
        $this->routes = new RouteCollection();
        $this->routes = $routes;
    }
    public function addMiddleware(Middleware $middleware)
    {
        $this->middlewares[] = $middleware;
    }
    abstract public function execute(Request $request);
    
}
