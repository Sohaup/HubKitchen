<?php

namespace PostApi\modules\HR\domain\services\employee;

use PostApi\modules\HR\app\DB\repositories\EmployeeRepository;

class DeleteEmployeeAction
{
    public static function execute(string $id)
    {
        $repo = new EmployeeRepository();
        $entity = $repo->findOne($id);
        if ($entity) {
            $repo->delete($id);
        }
    }
}
