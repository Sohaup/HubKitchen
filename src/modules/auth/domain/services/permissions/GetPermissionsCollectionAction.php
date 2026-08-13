<?php

namespace PostApi\modules\auth\domain\services\permissions;

use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetPermissionsCollectionAction
{
    /**  * @param Permission[]  */
    public static function execute(array $permissions)
    {
        $serin = SerializeToSerin::serializeCollection($permissions);
        return $serin;
    }
}
