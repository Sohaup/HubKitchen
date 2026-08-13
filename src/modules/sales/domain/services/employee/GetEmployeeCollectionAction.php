<?php

namespace PostApi\modules\sales\domain\services\employee;

use PostApi\modules\sales\app\DB\repositories\EmployeeRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetEmployeeCollectionAction
{
    public static function execute()
    {
        $employeeRepository = new EmployeeRepository();
        $employees = $employeeRepository->findAll();
        $serin = SerializeToSerin::serializeCollection($employees);
        return $serin;
    }
}
