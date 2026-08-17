<?php

namespace PostApi\modules\auth\domain\services\authirization;

use Error;
use Exception;
use PostApi\modules\auth\app\DB\repositories\PermissionRepository;
use PostApi\modules\auth\app\DB\repositories\RoleRepository;

class GrantPermissionAction
{
    public static function execute(int $roleId, int $permissionId)
    {
        try {
            $roleRepository = new RoleRepository();
            $permissionRepository = new PermissionRepository();
            $permissionId = $permissionId;
            $role = $roleRepository->findOne($roleId);
            $permission = $permissionRepository->findOne($permissionId);
            $roleRepository->grantPermission($role, $permission);
        } catch (Exception $err) {
            throw new Error($err->getMessage());
        }
    }
}
