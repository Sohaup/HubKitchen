<?php

namespace PostApi\modules\sales\domain\services\employee;

use PostApi\modules\sales\app\DB\repositories\EmployeeRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetEmployeeItemAction
{
    public static function execute(string $id)
    {
        $employeeRepository = new EmployeeRepository();
        $employee = $employeeRepository->findOne($id);
        $serin = SerializeToSerin::serialize($employee);
        return $serin;
    }
}
