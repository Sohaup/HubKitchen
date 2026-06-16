<?php

namespace PostApi\modules\HR\domain\services\employee;

use PostApi\modules\HR\app\DB\repositories\EmployeeRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetEmployeeItemAction
{
    public static function execute(string $id)
    {
        $repo = new EmployeeRepository();
        $item = $repo->findOne($id);
        return SerializeToSerin::serialize($item);
    }
}
