<?php
namespace PostApi\modules\auth\domain\services\Roles;

use PostApi\modules\auth\domain\Entities\Role;

class CreateRoleAction {
    public static function execute(string $name) {
        $role = new Role();
        $role->setName($name);
        return $role;
    }
}