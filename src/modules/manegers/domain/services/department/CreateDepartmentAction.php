<?php

namespace PostApi\modules\manegers\domain\services\department;

use PostApi\modules\manegers\app\DB\repositories\DepartmentRepository;
use PostApi\modules\manegers\domain\entities\Department;

class CreateDepartmentAction
{
    public static function execute(array $params): Department
    {        
        $name = $params['name'] ?? '';
        $department = new Department();
        $department->setName($name);
        $repo = new DepartmentRepository();
        $repo->create($department);
        return $department;
    }
}
