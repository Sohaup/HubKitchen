<?php

namespace PostApi\modules\auth\domain\services\user;

use Error;
use PostApi\modules\auth\app\DB\repositories\RoleRepository;
use PostApi\modules\auth\app\DB\repositories\UserRepository;
use PostApi\shared\helpers\command\ClousreCommand;
use PostApi\shared\helpers\command\Queue\TaskQueue;
use PostApi\shared\helpers\fecade\Files;
use PostApi\shared\helpers\fecade\Retery;

class UpdateUserAction
{
    public static function execute(string $id, array $userData)
    {
        $userRepo = new UserRepository();
        $user = $userRepo->findOne($id);
        $queue = new TaskQueue();
        if (!$user) {
            throw new Error("no user for this id");
        }
        if (isset($userData['name']) && !empty($userData['name'])) {
            $user->setName($userData['name']);
        }
        if (isset($userData['email']) && !empty($userData['email'])) {
            $user->setEmail($userData['email']);
        }
        if (isset($userData['password']) && !empty($userData['password'])) {
            $user->setPassword($userData['password']);
        }
        if (isset($userData['phone']) && !empty($userData['phone'])) {
            $user->setPhone($userData['phone']);
        }
        if (isset($userData['role_id']) && !empty($userData['role_id'])) {
            $roleRepo = new RoleRepository();
            $role = $roleRepo->findOne($userData['role_id']);
            $user->setRole($role);
        }
        if (isset($userData['avatar']) && !empty($userData['avatar'])) {
            $oldAvatar = $user->getAvatar();
            $queue->push(new ClousreCommand(function () use ($oldAvatar) {
                Retery::execute(function () use ($oldAvatar) {
                    Files::deleteFile($oldAvatar);
                });
            }));
            $queue->push(new ClousreCommand(function () {
                return Retery::execute(function () {
                    return Files::storeFile('avatar');
                });
            }));
            $results = $queue->execute();
            $user->setAvatar($results[1]);
        }
        $userRepo->update($user);
    }
}
