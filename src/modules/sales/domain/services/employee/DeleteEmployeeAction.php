<?php

namespace PostApi\modules\sales\domain\services\employee;

use PostApi\modules\sales\app\DB\repositories\EmployeeRepository;

class DeleteEmployeeAction
{
    public static function execute(string $id)
    {
        $employeeRepository = new EmployeeRepository();
        $employee = $employeeRepository->findOne($id);
        if ($employee) {
            $employeeRepository->delete($id);
        }
    }
}
