<?php

namespace PostApi\modules\auth\domain\services\Roles;


use PostApi\modules\auth\domain\Entities\Role;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetRoleItemAction
{
    public static function execute(Role $role)
    {
        $serin = SerializeToSerin::serialize($role);
        return $serin;
    }
}
