<?php

namespace PostApi\modules\auth\domain\services\user;

use PostApi\modules\auth\app\DB\repositories\RoleRepository;
use PostApi\modules\auth\app\DB\repositories\UserRepository;
use PostApi\modules\auth\app\DB\transactionManagers\UserUnit;
use PostApi\shared\helpers\command\ClousreCommand;
use PostApi\shared\helpers\command\Queue\TaskQueue;
use PostApi\shared\helpers\fecade\Files;
use PostApi\shared\helpers\fecade\Retery;

class CreateUserAction
{
    public static function execute(array $data)
    {
        $roleRepo = new RoleRepository();
        $userRepo = new UserRepository();
        $userUnit = new UserUnit($userRepo->getDbInstance());
        $queueo = new TaskQueue();
        $user = ValidateUserAction::execute($data['name'], $data['email'], $data['password'], $data['phone']);
        $role = $roleRepo->findOne($data['role_id']);
        $user->setRole($role);
        $queueo->push(new ClousreCommand(function () {
            $avatarPath = Retery::execute(function () {
                return Files::storeFile("avatar");
            });
            return $avatarPath;
        }));
        $results = $queueo->execute();
        $user->setAvatar($results[0]);
        $queueo->push(new ClousreCommand(function () use ($userUnit, $user) {
            $userUnit->registerNew($user);
            $userUnit->commit();
        }));
        $queueo->execute();
        return $user;
    }
}
