<?php

namespace PostApi\modules\CS\domain\services\role;

use PostApi\modules\CS\app\DB\repositories\RoleRepository;
use PostApi\modules\CS\domain\entities\Role;

class CreateRoleAction
{
    public static function execute(array $params): Role
    {        
        $name = $params['name'] ?? '';
        $role = new Role();
        $role->setName($name);
        $repo = new RoleRepository();
        $repo->create($role);
        return $role;
    }
}
