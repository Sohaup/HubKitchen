<?php

namespace PostApi\shared\app\http\routes;

use PostApi\shared\app\http\routes\Route\Route;
use PostApi\shared\app\http\routes\Route\RouteCollection;

class Router
{
    public RouteCollection $routes;
    public function __construct()
    {
        $this->routes = new RouteCollection();
    }
    public function addRoute(Route $route)
    {
        $this->routes->addRoute($route);
    }
    public function resolve(string $url, string $method)
    {        
        foreach ($this->routes as $route) {
            if ($route->path == $url && $route->httpMethod->value == $method) {
                $controller = new $route->controller();
                $method = $route->method;
                echo $controller->$method();
                return;
            }
        }
        http_response_code(404);
        die("404 - NOT FOUND");
    }
}
