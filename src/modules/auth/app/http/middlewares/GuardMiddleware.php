<?php

namespace PostApi\modules\auth\app\http\middlewares;

use Closure;
use Error;
use Exception;
use PostApi\shared\app\http\middlewares\Middleware;
use PostApi\shared\app\http\requests\Request;
use PostApi\shared\helpers\adapters\JWT;
use PostApi\shared\helpers\fecade\ViewError;

class GuardMiddleware implements Middleware
{    
    public function handle(Request $request, Closure $next)
    {        
        $headers = $request->headers;
       
        if (!isset($headers['Authorization'])) {
            header('HTTP/1.1 401 Unauthorized');
            echo ViewError::viewProplem("authorization error", "access denid", 1, "token required", 401);
            exit;
        }
        $authHeader = $headers['Authorization'];       
        if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            $token = $matches[1];            
            try {
                JWT::decode($token);                
                return $next($request);
            } catch (Error $error) {               
                header('HTTP/1.1 400 Bad Request');
                echo ViewError::viewProplem("authorization error", "access denid", 1, "unvalid token", 400);
                exit;
            }
        }
    }
}
