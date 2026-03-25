<?php
namespace PostApi\shared\app\http\middlewares;

use PostApi\shared\app\http\requests\Request;
use Closure;

class LoggerMiddleware implements Middleware {

    public function handle(Request $request, Closure $next)
    {
        echo $request->path;
        echo "\n";
        echo $request->method;
        return $next($request);
    }
}