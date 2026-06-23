<?php

namespace PostApi\modules\CS\domain\services\employee;

use PostApi\modules\CS\app\DB\repositories\EmployeeRepository;

class DeleteEmployeeAction
{
    public static function execute(string $id)
    {
        $repo = new EmployeeRepository();
        $employee = $repo->findOne($id);
        if ($employee) {
            $repo->delete($id);
        }
    }
}
