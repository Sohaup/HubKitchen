<?php
namespace PostApi\shared\app\http\middlewares;
use Closure;
use PostApi\shared\app\http\requests\Request;

interface Middleware {
    public function handle(Request $request , Closure $next); 
}