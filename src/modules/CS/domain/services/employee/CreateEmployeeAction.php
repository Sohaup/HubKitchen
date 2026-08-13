<?php

namespace PostApi\modules\CS\domain\services\employee;

use Error;
use PostApi\modules\CS\app\DB\repositories\EmployeeRepository;
use PostApi\modules\CS\domain\entities\Employee;
use PostApi\modules\CS\domain\entities\Role;


class CreateEmployeeAction
{
    public static function execute(array $params): Employee
    {        
        $userId = $params['user_id'] ?? null;
        $hrEmployeeId = $params['employee_id'] ?? null;
        $roleId = $params['role_id'] ?? null;
        $repo = new EmployeeRepository();
        $employee = new Employee();
        $employee->setUserId($userId);
        if ($hrEmployeeId) {
            $employee->setEmployeeId($hrEmployeeId);
        } else {
            throw new Error("the employee and user did not match");
        }
        if ($roleId) {
            $role = new Role();
            $role->setId($roleId);
            $employee->setRole($role);
        }
        $repo->create($employee);
        return $employee;
    }
}
