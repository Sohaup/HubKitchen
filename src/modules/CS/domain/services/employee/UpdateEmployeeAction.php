<?php

namespace PostApi\modules\CS\domain\services\employee;

use PostApi\modules\CS\app\DB\repositories\EmployeeRepository;
use PostApi\shared\app\http\requests\Request;

class UpdateEmployeeAction
{
    public static function execute(string $id)
    {
        $request = new Request();
        $params = $request->body;
        $repo = new EmployeeRepository();
        $employee = $repo->findOne($id);
        if (!$employee) throw new \Exception('employee not found');
        if (isset($params['user_id'])) {
            $userRepo = new \PostApi\modules\auth\app\DB\repositories\UserRepository();
            $user = $userRepo->findOne($params['user_id']);
            $employee->setUser($user);
        }
        if (isset($params['hr_employee_id'])) {
            $hr = new \PostApi\modules\HR\domain\entities\Employee();
            $hr->setId($params['hr_employee_id']);
            $employee->setEmployee($hr);
        }
        if (isset($params['role_id'])) {
            $role = new \PostApi\modules\CS\domain\entities\Role();
            $role->setId($params['role_id']);
            $employee->setRole($role);
        }
        $repo->update($employee);
        return $employee;
    }
}
