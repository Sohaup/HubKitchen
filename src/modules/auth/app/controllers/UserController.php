<?php

namespace PostApi\modules\auth\app\controllers;

use Error;
use Exception;
use PostApi\modules\auth\app\DB\repositories\UserRepository;
use PostApi\modules\auth\domain\Entities\User;
use PostApi\modules\auth\domain\services\authirization\CheckUserAuthorizaidAction;
use PostApi\modules\auth\domain\services\user\AssignRoleToUserAction;
use PostApi\modules\auth\domain\services\user\GetUserItemAction;
use PostApi\modules\auth\domain\services\user\GetUsersCollectionAction;
use PostApi\modules\auth\domain\services\user\ValidateUserAction;
use PostApi\shared\app\controllers\api\ApiControllerContract;
use PostApi\shared\app\http\requests\Request;
use PostApi\shared\app\http\responses\success\json\Json;
use PostApi\shared\helpers\fecade\ViewError;

class UserController implements ApiControllerContract
{
    public function index()
    {
       /**
         *  @return User[]
         */
        $userRepository = new UserRepository();
        $users = $userRepository->findAll();
        if ($users) {
            $serin = GetUsersCollectionAction::execute($users);
            http_response_code(200);
            return Json::toJson($serin);
        } else {
            return Json::toJson("no users yet");
        }
    }
    public function get(string $id)
    {
       $userRepository = new UserRepository();
       $user = $userRepository->findOne($id);
       
       $serin = GetUserItemAction::execute($user);
       http_response_code(200);
       return Json::toJson($serin);
    }
    public function create()
    {
        header("Content-Type: application/json");
        $userRepository = new UserRepository();
        $request = new Request();
        $params = $request->body;
        if (isset($params['name'], $params['email'], $params['password'], $params['phone'] , $params['role_id'])) {
            try {
                $user =  ValidateUserAction::execute($params['name'], $params['email'], $params['password'], $params['phone']);
                $userWithRole = AssignRoleToUserAction::execute($user , $params['role_id']);
                $userRepository->create($userWithRole);
                http_response_code(201);
                $serin = GetUserItemAction::execute($user);
                return Json::toJson($serin);
            } catch (Exception $error) {
                return ViewError::viewProplem("creating user error" , "validation error" , 1 , $error->getMessage() , 400); 
            }
        } else {
            return ViewError::viewProplem("creating user error" , "missing paramters error" , 1 , "some required paramters are missing", 400);
        }
    }
    public function update(string $id)
    {
        header("Content-Type: application/json");
        $userRepository = new UserRepository();
        $request = new Request();
        $params = $request->body;
        if (isset($params['name'], $params['email'], $params['password'], $params['phone'])) {
            try {
                $user = $userRepository->findOne($id);
                $isAuthrizaid = CheckUserAuthorizaidAction::execute($id);
                if ($user && $isAuthrizaid) {
                    $user = ValidateUserAction::execute($params['name'], $params['email'], $params['password'], $params['phone'] );
                    $userWithRole = AssignRoleToUserAction::execute($user , $params['role_id']);
                    $userWithRole->setId($id);
                    $userRepository->update($userWithRole);
                    http_response_code(200);
                    return Json::toJson(['message' => "user updated successfuly"]);
                } else {
                   return ViewError::viewProplem("updating user error" , "not valid param error" , 1 , "no corresponding user for this id" , 400);
                }
            } catch (Exception $error) {
                echo $error->getFile();
                echo "\n";
                echo $error->getLine();
                echo "\n";
               return ViewError::viewProplem("updaing user error" , "validation error" , 1 , $error->getMessage() , 400);
            }
        } else {
            return ViewError::viewProplem("updating user error" , "missing paramters error" , 1 , "missing some required paramters for updating user" , 400);
        }
    }
    public function delete(string $id)
    {
        header("Content-Type: application/json");
        $userRepository = new UserRepository();
        try {
            $user = $userRepository->findOne($id);
            $isAuthrizaid = CheckUserAuthorizaidAction::execute($id);
            if ($user && $isAuthrizaid) {
                $userRepository->delete($id);
                http_response_code(200);
                return Json::toJson(['message' => "user deleted successfuly"]);
            }
        } catch (Error $error) {
             return ViewError::viewProplem("deleting user error" , "not valid param error" , 1 , "no corresponding user for this id" , 400);
        }
    }
}
