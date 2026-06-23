<?php

namespace PostApi\modules\CS\domain\services\role;

use PostApi\modules\CS\app\DB\repositories\RoleRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetRoleItemAction
{
    public static function execute(int $id)
    {
        $repo = new RoleRepository();
        $item = $repo->findOne($id);
        return SerializeToSerin::serialize($item);
    }
}
