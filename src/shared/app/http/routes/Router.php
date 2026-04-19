<?php

namespace PostApi\shared\app\http\routes;

use PostApi\shared\app\http\routes\Route\Route;
use PostApi\shared\app\http\routes\Route\RouteCollection;
use PostApi\shared\helpers\fecade\Urls;

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
        $pattern = '/postApi/[a-z]*/(?P<id>[a-f0-9\-]+)$';
        $path = Urls::transformUrl(parse_url($url, PHP_URL_PATH));           
        foreach ($this->routes as $route) {            
            if ($route->path == $path && $route->httpMethod->value == $method) {
                $controller = new $route->controller();
                $method = $route->method;
                echo $controller->$method();
                return;
            } elseif (preg_match('~' . $pattern . '~', $url, $matches)) {
                $routePattern = '/:id$';
                if (preg_match('~' . $routePattern . '~', $route->path,  $RouteMatches)) {
                    $staticRoute = preg_replace('~' . $routePattern . '~', "/" . $matches['id'], $route->path);
                    if ($staticRoute == $url && $route->httpMethod->value == $method) {
                        $controller = new $route->controller();
                        $method = $route->method;
                        echo $controller->$method($matches['id']);
                        return;
                    }
                }
            }
        }
       
        http_response_code(404);
        die("404 - NOT FOUND");
    }
}
