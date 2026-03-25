<?php

namespace PostApi\shared\app\http\proxies;

use PostApi\shared\app\http\middlewares\Middleware;
use PostApi\shared\app\http\requests\Request;

class ProxyMiddlewareForRoute
{
    public array $middlewares = [];
    public function __construct(public string $path) {}
    public function addMiddleware(Middleware $middleware)
    {
        $this->middlewares[] = $middleware;
    }
    public function execute(Request $request)
    {
        if ($request->path == $this->path) {
            $coreLogic = function ($req) {
                return "ERP Data for: $req->path";
            };

            $pipeline = array_reduce(
                array_reverse($this->middlewares),
                function ($stack, $middleware) {
                    return function ($request) use ($stack, $middleware) {
                        return $middleware->handle($request, $stack);
                    };
                },
                $coreLogic
            );

            return $pipeline($request);
        }
        return $request;
    }
}
