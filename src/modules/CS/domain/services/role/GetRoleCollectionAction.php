<?php

namespace PostApi\modules\CS\domain\services\role;

use PostApi\modules\CS\app\DB\repositories\RoleRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetRoleCollectionAction
{
    public static function execute()
    {
        $repo = new RoleRepository();
        $items = $repo->findAll();
        return SerializeToSerin::serializeCollection($items);
    }
}
