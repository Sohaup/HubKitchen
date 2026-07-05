<?php

namespace PostApi\modules\manegers\domain\services\department;

use PostApi\modules\manegers\app\DB\repositories\DepartmentRepository;

class DeleteDepartmentAction
{
    public static function execute(int $id)
    {
        $repo = new DepartmentRepository();
        $repo->delete($id);
    }
}
