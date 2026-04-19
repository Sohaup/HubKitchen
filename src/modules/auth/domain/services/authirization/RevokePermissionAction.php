<?php 
namespace PostApi\modules\auth\domain\services\authirization;

use PostApi\modules\auth\app\DB\repositories\PermissionRepository;
use PostApi\modules\auth\app\DB\repositories\RoleRepository;

class RevokePermissionAction {
    public static function execute(int $roleId , int $permissionId) {
        $roleRepository = new RoleRepository();
        $permissionRepository = new PermissionRepository();
        $role = $roleRepository->findOne($roleId);
        $permission = $permissionRepository->findOne($permissionId);
        $roleRepository->revokePermission($role , $permission);
    }
}