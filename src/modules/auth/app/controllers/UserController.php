<?php

namespace PostApi\modules\auth\app\controllers;

use Error;
use Exception;
use PostApi\modules\auth\app\DB\repositories\UserRepository;
use PostApi\modules\auth\app\DB\transactionManagers\UserUnit;
use PostApi\modules\auth\domain\Entities\User;
use PostApi\modules\auth\domain\services\authirization\CheckUserAuthorizaidAction;
use PostApi\modules\auth\domain\services\user\AssignRoleToUserAction;
use PostApi\modules\auth\domain\services\user\CreateUserAction;
use PostApi\modules\auth\domain\services\user\GetUserItemAction;
use PostApi\modules\auth\domain\services\user\GetUsersCollectionAction;
use PostApi\modules\auth\domain\services\user\UpdateUserAction;
use PostApi\modules\auth\domain\services\user\ValidateUserAction;
use PostApi\shared\app\controllers\api\ApiControllerContract;
use PostApi\shared\app\http\requests\Request;
use PostApi\shared\app\http\responses\success\json\Json;
use PostApi\shared\helpers\command\ClousreCommand;
use PostApi\shared\helpers\command\Queue\TaskQueue;
use PostApi\shared\helpers\fecade\Chache;
use PostApi\shared\helpers\fecade\Files;
use PostApi\shared\helpers\fecade\Retery;
use PostApi\shared\helpers\fecade\ViewError;

class UserController implements ApiControllerContract
{
    public function index(Request $request)
    {
        /**
         *  @return User[]
         */
        
        $body = $request->body;
        $userRepository = new UserRepository();
        $critiria = [];
        $serin = "";
        if (isset($body['name'])) {
            $critiria['name'] = $body['name'];
        }
        if (isset($body['email'])) {
            $critiria['email'] = $body['email'];
        }
        if (isset($body['phone'])) {
            $critiria['phone'] = $body['phone'];
        }
        if (isset($body['role_id'])) {
            $critiria['role_id'] = $body['role_id'];
        }

        if (!empty($critiria)) {
            $users = $userRepository->findBy($critiria);
            $serin = GetUsersCollectionAction::execute($users);
        } else {
            $users = $userRepository->findAll();
            $serin = GetUsersCollectionAction::execute($users);
        }

        http_response_code(200);
        return Chache::checkCache($serin);
    }
    public function get(string $id)
    {
        $userRepository = new UserRepository();
        $user = $userRepository->findOne($id);

        $serin = GetUserItemAction::execute($user);
        http_response_code(200);
        return Json::toJson($serin);
    }
    public function create(Request $request)
    {
        header("Content-Type: application/json");
        $userRepository = new UserRepository();       
        $params = $request->body;
        if (isset($params['name'], $params['email'], $params['password'], $params['phone'], $params['role_id'], $request->files['avatar'])) {
            try {
                $user = CreateUserAction::execute($params);
                http_response_code(201);
                $serin = GetUserItemAction::execute($user);
                return Json::toJson($serin);
            } catch (Exception $error) {
                return ViewError::viewProplem("creating user error", "validation error", 1, $error->getMessage(), 400);
            }
        } else {
            return ViewError::viewProplem("creating user error", "missing paramters error", 1, "some required paramters are missing", 400);
        }
    }
    public function update(Request $request , string $id)
    {
        header("Content-Type: application/json");
        $userRepository = new UserRepository();       
        $params = $request->body;
        $avatarPath = "";
        if ($request->files['avatar']) {
            $avatarPath =  Files::storeFile('avatar');
        }
        $userData = ['name' => $params['name'], 'role_id' => $params['role_id'], 'email' => $params['email'], 'password' => $params['password'], 'phone' => $params['phone'], 'avatar' => $avatarPath];
        try {
            $user = $userRepository->findOne($id);
            // $isAuthrizaid = CheckUserAuthorizaidAction::execute($id);
            if ($user) {
                $user = ValidateUserAction::execute($params['name'], $params['email'], $params['password'], $params['phone']);
                UpdateUserAction::execute($id, $userData);
                http_response_code(200);
                return Json::toJson(['message' => "user updated successfuly"]);
            } else {
                return ViewError::viewProplem("updating user error", "not valid param error", 1, "no corresponding user for this id", 400);
            }
        } catch (Exception $error) {
            return ViewError::viewProplem("updaing user error", "validation error", 1, $error->getMessage(), 400);
        }
    }
    public function delete(string $id)
    {
        header("Content-Type: application/json");
        $userRepository = new UserRepository();
        $userUnit = new UserUnit($userRepository->getDbInstance());
        $queue = new TaskQueue();
        try {
            $user = $userRepository->findOne($id);
            // $isAuthrizaid = CheckUserAuthorizaidAction::execute($id);
            if ($user) {
                $queue->push(new ClousreCommand(function () use ($user) {
                    Retery::execute(function () use ($user) {
                        Files::deleteFile($user->getAvatar());
                    });
                }));
                $queue->push(new ClousreCommand(function () use ($userUnit, $user) {
                    $userUnit->registerDeleted($user);
                    $userUnit->commit();
                }));
                $queue->execute();
                http_response_code(200);
                return Json::toJson(['message' => "user deleted successfuly"]);
            }
        } catch (Error $error) {
            return ViewError::viewProplem("deleting user error", "not valid param error", 1, "no corresponding user for this id", 400);
        }
    }
}
