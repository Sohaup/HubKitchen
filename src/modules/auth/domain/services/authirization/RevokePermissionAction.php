<?php

namespace PostApi\modules\auth\domain\services\authirization;

use Error;
use Exception;
use PostApi\modules\auth\app\DB\repositories\PermissionRepository;
use PostApi\modules\auth\app\DB\repositories\RoleRepository;

class RevokePermissionAction
{
    public static function execute(int $roleId, int $permissionId)
    {
        try {
            $roleRepository = new RoleRepository();
            $permissionRepository = new PermissionRepository();
            $role = $roleRepository->findOne($roleId);
            $permission = $permissionRepository->findOne($permissionId);
            $roleRepository->revokePermission($role, $permission);
        } catch (Exception $err) {
            throw new Error($err->getMessage());
        }
    }
}
