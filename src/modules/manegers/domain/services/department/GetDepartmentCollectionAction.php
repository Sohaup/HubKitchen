<?php

namespace PostApi\modules\manegers\domain\services\department;

use PostApi\modules\manegers\app\DB\repositories\DepartmentRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetDepartmentCollectionAction
{
    public static function execute()
    {
        $repo = new DepartmentRepository();
        $items = $repo->findAll();
        $serin = SerializeToSerin::serializeCollection($items);
        return $serin;
    }
}
