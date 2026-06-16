<?php

namespace PostApi\modules\HR\domain\services\departments;

use PostApi\modules\HR\app\DB\repositories\DepartmentRepository;

class DeleteDepartmentAction
{
    public static function execute(int $id)
    {
        $departmentsRepository = new DepartmentRepository();
        $department = $departmentsRepository->findOne($id);
        if ($department) {
            $departmentsRepository->delete($id);
        }
    }
}
