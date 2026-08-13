<?php

namespace PostApi\modules\manegers\domain\services\department;

use PostApi\modules\manegers\app\DB\repositories\DepartmentRepository;


class UpdateDepartmentAction
{
    public static function execute(int $id , array $params)
    {       
        $repo = new DepartmentRepository();
        $department = $repo->findOne($id);
        if (!$department) {
            throw new \Exception("department not found");
        }
        if (isset($params['name'])) {
            $department->setName($params['name']);
        }
        $repo->update($department);
        return $department;
    }
}
