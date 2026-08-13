<?php
namespace PostApi\modules\auth\domain\services\permissions;

use PostApi\modules\auth\domain\Entities\Permission;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetPermissionItemAction {
    public static function execute(Permission $permission) {
        $serin = SerializeToSerin::serialize($permission);
        return $serin;
    }
}