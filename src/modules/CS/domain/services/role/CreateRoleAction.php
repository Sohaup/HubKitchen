<?php

namespace PostApi\modules\CS\domain\services\role;

use PostApi\modules\CS\app\DB\repositories\RoleRepository;
use PostApi\modules\CS\domain\entities\Role;
use PostApi\shared\app\http\requests\Request;

class CreateRoleAction
{
    public static function execute(): Role
    {
        $request = new Request();
        $params = $request->body;
        $name = $params['name'] ?? '';
        $role = new Role();
        $role->setName($name);
        $repo = new RoleRepository();
        $repo->create($role);
        return $role;
    }
}
