<?php

namespace PostApi\modules\auth\app\http\middlewares;

use Closure;
use PostApi\shared\app\http\middlewares\Middleware;
use PostApi\shared\app\http\requests\Request;
use PostApi\shared\helpers\adapters\JWT;
use PostApi\shared\helpers\fecade\ViewError;

class GateMiddleware implements Middleware
{
    public function __construct(private array $roles) {}
    public function handle(Request $request, Closure $next)
    {
        $headers = $request->headers;
        $authHeader = $headers['Authorization'];
        if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            $token = $matches[1];
            $decodedToken = JWT::decode($token);
            $user = $decodedToken->user;
            $role = $user->role;            
            foreach ($this->roles as $acceptrole) {
                if ($role == $acceptrole->value) {
                    return $next($request);
                }
            }
            header('HTTP/1.1 403 Forbidden');
            echo ViewError::viewProplem("authorization error", "access denid", 1, "forbidden", 403);
            exit;
        }
    }
}
