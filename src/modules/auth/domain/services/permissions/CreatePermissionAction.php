<?php
namespace PostApi\modules\auth\domain\services\permissions;

use PostApi\modules\auth\domain\Entities\Permission;

class CreatePermissionAction {
    public static function execute(string $name) {
        $permission = new Permission();
        $permission->setName($name);
        return $permission;
    }
}