<?php

namespace PostApi\modules\CS\domain\services\employee;

use PostApi\modules\CS\app\DB\repositories\EmployeeRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetEmployeeCollectionAction
{
    public static function execute()
    {
        $repo = new EmployeeRepository();
        $items = $repo->findAll();
        return SerializeToSerin::serializeCollection($items);
    }
}
