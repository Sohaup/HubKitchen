<?php

namespace PostApi\modules\auth\app\controllers;

use Exception;
use PostApi\modules\auth\domain\services\authentication\LogInWithCredentialsAction;
use PostApi\modules\auth\domain\services\authentication\LogInWithGoogleAction;
use PostApi\shared\app\http\requests\Request;
use PostApi\shared\app\http\responses\success\json\Json;
use PostApi\shared\helpers\fecade\Redirect;
use PostApi\shared\helpers\fecade\Urls;
use PostApi\shared\helpers\fecade\ViewError;

class AuthController
{
    public function register()
    {
        Redirect::ToRoute(Urls::transformRouteUrl("/users/create"));
    }

    public function logIn()
    {
        header("Content-Type:application/json");
        $request = new Request();
        $params = $request->body;
        if (isset($params['email'], $params['password'])) {
            try {
                $loginAction = new LogInWithCredentialsAction();
                $jwtToken = $loginAction->getToken();
                http_response_code(200);
                return Json::toJson(['message' => 'login successfuly', 'token' => $jwtToken]);
            } catch (Exception $error) {
                return ViewError::viewProplem("login error", "missing reqired paramters error", 1, $error->getMessage(), 400);
            }
        } else {
            return ViewError::viewProplem("login error", "missing reqired paramters error", 1, "email and password are required", 400);
        }
    }

    public function loginWithGoogle()
    {
        try {
            $loginAction = new LogInWithGoogleAction();
            $token = $loginAction->getToken();
            http_response_code(200);
            return Json::toJson(['message' => 'logged in successfuly', 'token' => $token]);
        } catch (Exception $error) {
            return ViewError::viewProplem("google login error" , "internal error" , 1 , $error->getMessage() , 500);
        }
    }
}
