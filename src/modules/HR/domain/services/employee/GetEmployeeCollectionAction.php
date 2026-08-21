<?php

namespace PostApi\modules\HR\domain\services\employee;

use PostApi\modules\HR\app\DB\repositories\EmployeeRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetEmployeeCollectionAction
{
    public static function execute(array $items = null)
    {
        $repo = new EmployeeRepository();
        return SerializeToSerin::serializeCollection($items ?? $repo->findAll());
    }
}
