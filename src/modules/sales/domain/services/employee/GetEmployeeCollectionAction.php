<?php

namespace PostApi\modules\sales\domain\services\employee;

use PostApi\modules\sales\app\DB\repositories\EmployeeRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetEmployeeCollectionAction
{
    public static function execute(array $items)
    {
        $employeeRepository = new EmployeeRepository();
        $serin = SerializeToSerin::serializeCollection($items ?? $employeeRepository->findAll());
        return $serin;
    }
}
