<?php

namespace PostApi\modules\CS\domain\services\role;

use PostApi\modules\CS\app\DB\repositories\RoleRepository;

class DeleteRoleAction
{
    public static function execute(int $id)
    {
        $repo = new RoleRepository();
        $role = $repo->findOne($id);
        if ($role) {
            $repo->delete($id);
        }
    }
}
