<?php

namespace PostApi\shared\app\http\routes;

use PostApi\shared\app\http\requests\Request;
use PostApi\shared\app\http\routes\Route\Route;
use PostApi\shared\app\http\routes\Route\RouteCollection;
use PostApi\shared\helpers\fecade\Urls;
use ReflectionMethod;

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
        $pattern = '/postApi/[a-zA-Z\-]*/(?P<id>[a-f0-9\-]+)$';
        $path = Urls::transformUrl(parse_url($url, PHP_URL_PATH));
        foreach ($this->routes as $route) {
            if ($route->path == $path && $route->httpMethod->value == $method) {
                $controller = new $route->controller();
                $method = $route->method;
                $request = new Request();
                echo $controller->$method($request);
                return;
            } elseif (preg_match('~' . $pattern . '~', $url, $matches)) {
                $routePattern = '/:id$';
                if (preg_match('~' . $routePattern . '~', $route->path,  $RouteMatches)) {
                    $staticRoute = preg_replace('~' . $routePattern . '~', "/" . $matches['id'], $route->path);
                    if ($staticRoute == $url && $route->httpMethod->value == $method) {
                        return $this->manegeMethodArgs($route, $matches['id']);
                    }
                }
            }
        }

        http_response_code(404);
        die("404 - NOT FOUND");
    }

    public function manegeMethodArgs(Route $route, string $id)
    {
        $controller = new $route->controller();
        $method = $route->method;
        $funcRef = new ReflectionMethod($controller, $method);
        $paretmeters = $funcRef->getParameters();
        $request = new Request();
        if ($paretmeters[0]->getType() == "string") {
            echo $controller->$method($id, $request);
            return;
        }
        echo $controller->$method($request, $id);
        return;
    }
}
