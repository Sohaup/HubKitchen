<?php

namespace PostApi\modules\CS\domain\services\employee;

use Error;
use PostApi\modules\CS\app\DB\repositories\EmployeeRepository;
use PostApi\modules\auth\app\DB\repositories\UserRepository;
use PostApi\modules\CS\domain\entities\Employee;
use PostApi\modules\CS\domain\entities\Role;
use PostApi\modules\HR\app\DB\repositories\EmployeeRepository as RepositoriesEmployeeRepository;
use PostApi\shared\app\http\requests\Request;

class CreateEmployeeAction
{
    public static function execute(): Employee
    {
        $request = new Request();
        $params = $request->body;
        $userId = $params['user_id'] ?? null;
        $hrEmployeeId = $params['employee_id'] ?? null;
        $roleId = $params['role_id'] ?? null;
        $userRepo = new UserRepository();
        $repo = new EmployeeRepository();
        $hrEmployeeRepo = new RepositoriesEmployeeRepository();
        $user = $userRepo->findOne($userId);
        $employee = new Employee();
        $employee->setUser($user);
        $hrEmployee = $hrEmployeeRepo->findOne($hrEmployeeId);

        if ($employee->getUser()->getId() == $user->getId()) {
            $employee->setEmployee($hrEmployee);
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
