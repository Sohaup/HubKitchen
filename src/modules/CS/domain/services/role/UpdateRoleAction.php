<?php

namespace PostApi\modules\CS\domain\services\role;

use PostApi\modules\CS\app\DB\repositories\RoleRepository;

class UpdateRoleAction
{
    public static function execute(int $id , array $params)
    {       
        $repo = new RoleRepository();
        $role = $repo->findOne($id);
        if (!$role) throw new \Exception('role not found');
        if (isset($params['name'])) $role->setName($params['name']);
        $repo->update($role);
        return $role;
    }
}
