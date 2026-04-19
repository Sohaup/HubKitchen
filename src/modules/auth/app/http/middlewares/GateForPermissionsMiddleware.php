<?php

namespace PostApi\modules\auth\app\http\middlewares;

use Closure;
use PostApi\modules\auth\app\DB\repositories\RoleRepository;
use PostApi\modules\auth\app\DB\repositories\UserRepository;
use PostApi\shared\app\http\middlewares\Middleware;
use PostApi\shared\app\http\requests\Request;
use PostApi\shared\helpers\adapters\JWT;
use PostApi\shared\helpers\fecade\ViewError;

class GateForPermissionsMiddleware implements Middleware
{
    public function __construct(private array $permissions = []) {}
    public function handle(Request $request, Closure $next)
    {
        $headers = $request->headers;
        $authHeader = $headers['Authorization'];
        $userRepository  = new UserRepository();
        $roleRepository = new RoleRepository();
        if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            $token = $matches[1];
            $decodedToken = JWT::decode($token);
            $userId = $decodedToken->user->id;
            $user = $userRepository->findOne($userId);
            $role = $roleRepository->findOne($user->getRole()->getId());
            $rolePermissions = $roleRepository->getRolePermissions($role);            
            foreach ($this->permissions as $permission) {
                foreach ($rolePermissions->permissions as $rolePermission) {
                    if ($rolePermission->getName() == $permission) {
                        return $next($request);
                    }
                }
            }
            header('HTTP/1.1 403 Forbidden');
            echo ViewError::viewProplem("authorization error", "access denid", 1, "forbidden", 403);
            exit;
        }
    }
}
