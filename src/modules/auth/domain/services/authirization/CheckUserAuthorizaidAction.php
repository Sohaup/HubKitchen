<?php

namespace PostApi\modules\auth\domain\services\authirization;

use PostApi\shared\app\http\requests\Request;
use PostApi\shared\helpers\adapters\JWT;
use PostApi\shared\helpers\fecade\ViewError;

class CheckUserAuthorizaidAction
{
    public static function execute(string $userId)
    {
        $request = new Request();
        if (!$request->getToken()) {
            exit(400);
        }        
        $decoded = JWT::decode($request->getToken());
        $id  = $decoded->user->id;
        if ($userId == $id) {
            return true;
        } else {
            header('HTTP/1.1 403 Forbidden');
            echo ViewError::viewProplem("authorization error", "access denid", 1, "forbidden", 403);
            exit;
        }
    }
}
