<?php

namespace PostApi\modules\CS\domain\services\role;

use PostApi\modules\CS\app\DB\repositories\RoleRepository;
use PostApi\shared\app\http\requests\Request;

class UpdateRoleAction
{
    public static function execute(int $id)
    {
        $request = new Request();
        $params = $request->body;
        $repo = new RoleRepository();
        $role = $repo->findOne($id);
        if (!$role) throw new \Exception('role not found');
        if (isset($params['name'])) $role->setName($params['name']);
        $repo->update($role);
        return $role;
    }
}
