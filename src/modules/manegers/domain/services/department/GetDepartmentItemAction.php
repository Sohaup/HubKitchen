<?php

namespace PostApi\modules\manegers\domain\services\department;

use PostApi\modules\manegers\app\DB\repositories\DepartmentRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetDepartmentItemAction
{
    public static function execute(int $id)
    {
        $repo = new DepartmentRepository();
        $item = $repo->findOne($id);
        $serin = SerializeToSerin::serialize($item);
        return $serin;
    }
}
