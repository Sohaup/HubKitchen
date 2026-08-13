<?php

namespace PostApi\modules\auth\domain\services\Roles;

use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetRoleCollectionAction
{
    /**  * @param Role[]  */
    public static function execute(array $roles)
    {
        $serin = SerializeToSerin::serializeCollection($roles);
        return $serin;
    }
}
