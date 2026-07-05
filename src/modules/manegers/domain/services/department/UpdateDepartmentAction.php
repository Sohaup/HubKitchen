<?php

namespace PostApi\modules\manegers\domain\services\department;

use PostApi\modules\manegers\app\DB\repositories\DepartmentRepository;
use PostApi\shared\app\http\requests\Request;

class UpdateDepartmentAction
{
    public static function execute(int $id)
    {
        $request = new Request();
        $params = $request->body;
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
