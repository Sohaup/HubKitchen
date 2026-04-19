<?php
namespace PostApi\modules\auth\domain\services\user;

use Error;
use PostApi\modules\auth\app\DB\repositories\RoleRepository;
use PostApi\modules\auth\domain\Entities\User;

class AssignRoleToUserAction {
    /**
     * @return User
     */
    public static function execute(User $user , int $role_id) {
        $roleRepository = new RoleRepository();
        try {
            $userRole = $roleRepository->findOne($role_id);
            $user->setRole($userRole);
            return $user;
        } catch (Error $error) {
            throw new Error("there is no corrosponding role for this role_id ");
        }
    }
}