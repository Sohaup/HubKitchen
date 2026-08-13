<?php

namespace PostApi\modules\sales\domain\services\employee;

use PostApi\modules\sales\app\DB\repositories\EmployeeRepository;
use PostApi\modules\sales\domain\entities\Employee;

class UpdateEmployeeAction
{
    public static function execute(Employee $employee)
    {
        $employeeRepository = new EmployeeRepository();
        $employeeRepository->update($employee);
    }
}
