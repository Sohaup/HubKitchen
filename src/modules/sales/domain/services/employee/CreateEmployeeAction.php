<?php

namespace PostApi\modules\sales\domain\services\employee;

use PostApi\modules\sales\app\DB\repositories\EmployeeRepository;
use PostApi\modules\sales\domain\entities\Employee;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class CreateEmployeeAction
{
    public static function execute(Employee $employee)
    {
        $employeeRepository = new EmployeeRepository();
        $employeeRepository->create($employee);
        $serin = SerializeToSerin::serialize($employee);
        return $serin;
    }
}
