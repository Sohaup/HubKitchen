<?php

namespace PostApi\shared\app\http\proxies;

use PostApi\shared\app\http\requests\Request;
use PostApi\shared\app\http\routes\Route\Route;
use PostApi\shared\helpers\fecade\Urls;

class ProxyMiddlewareForRoute extends ProxyDecorator
{
    public array $middlewares = [];
    public function execute(Request $request)
    {

        foreach ($this->routes as $route) {
            $routePattern = '/:id$';
            $pattern = '/postApi/[a-z]*/(?P<id>[a-f0-9\-]+)$';
            $path = Urls::transformUrl(parse_url($route->path, PHP_URL_PATH));            
            if ($request->path == $path && $route->httpMethod->value == $request->method) {               
                return $this->executeLogic($request, $route);
            } elseif (preg_match('~' . $pattern . '~', $request->path, $matches)) {
                if (preg_match('~' . $routePattern . '~', $route->path,  $RouteMatches)) {
                    $staticRoute = preg_replace('~' . $routePattern . '~', "/" . $matches['id'], $route->path);
                    if ($staticRoute == $request->path && $route->httpMethod->value == $request->method) {
                        return  $this->executeLogic($request, $route);
                    }
                }
            }
        }
        return $request;
    }

    public function executeLogic(Request $request, Route $route)
    {
        $coreLogic = function ($req) {
            return "ERP Data for: $req->path";
        };

        $pipeline = array_reduce(
            array_reverse($route->middleares->middlewares),
            function ($stack, $middleware) {
                return function ($request) use ($stack, $middleware) {
                    return $middleware->handle($request, $stack);
                };
            },
            $coreLogic
        );

        return $pipeline($request);
    }
}
