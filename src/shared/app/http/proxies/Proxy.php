<?php
namespace PostApi\shared\app\http\proxies;

use PostApi\shared\app\http\middlewares\Middleware;
use PostApi\shared\app\http\requests\Request;

interface Proxy {
    public function addMiddleware(Middleware $middleware);
    public function execute(Request $request);
}