<?php

namespace PostApi\modules\CS\domain\services\employee;

use PostApi\modules\CS\app\DB\repositories\EmployeeRepository;
use PostApi\modules\CS\domain\entities\Role;

class UpdateEmployeeAction
{
    public static function execute(string $id , array $params)
    {       
        $repo = new EmployeeRepository();
        $employee = $repo->findOne($id);
        if (!$employee) throw new \Exception('employee not found');
        if (isset($params['user_id'])) {
            $employee->setUserId($params['user_id']);
        }
        if (isset($params['hr_employee_id'])) {
            $employee->setEmployeeId($params['hr_employee_id']);
        }
        if (isset($params['role_id'])) {
            $role = new Role();
            $role->setId($params['role_id']);
            $employee->setRole($role);
        }
        $repo->update($employee);
        return $employee;
    }
}
