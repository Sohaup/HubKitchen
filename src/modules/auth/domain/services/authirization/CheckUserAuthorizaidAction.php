<?php

namespace PostApi\modules\auth\domain\services\authirization;

use Error;
use Exception;
use PostApi\shared\helpers\adapters\JWT;
use PostApi\shared\helpers\fecade\ViewError;

class CheckUserAuthorizaidAction
{
    public static function execute(string $userId, string $token)
    {
        try {
            if (!$token) {
                exit(400);
            }
            $decoded = JWT::decode($token);
            $id  = $decoded->user->id;
            if ($userId == $id) {
                return true;
            } else {
                header('HTTP/1.1 403 Forbidden');
                echo ViewError::viewProplem("authorization error", "access denid", 1, "forbidden", 403);
                exit;
            }
        } catch (Exception $err) {
            throw new Error($err->getMessage());
        }
    }
}
