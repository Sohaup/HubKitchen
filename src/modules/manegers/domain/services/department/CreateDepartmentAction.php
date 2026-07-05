<?php

namespace PostApi\modules\manegers\domain\services\department;

use PostApi\modules\manegers\app\DB\repositories\DepartmentRepository;
use PostApi\modules\manegers\domain\entities\Department;
use PostApi\shared\app\http\requests\Request;

class CreateDepartmentAction
{
    public static function execute(): Department
    {
        $request = new Request();
        $params = $request->body;
        $name = $params['name'] ?? '';
        $department = new Department();
        $department->setName($name);
        $repo = new DepartmentRepository();
        $repo->create($department);
        return $department;
    }
}
