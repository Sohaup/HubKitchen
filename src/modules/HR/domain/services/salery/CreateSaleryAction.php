<?php

namespace PostApi\modules\HR\domain\services\salery;

use PostApi\modules\HR\app\DB\repositories\SaleryRepository;
use PostApi\modules\HR\app\DB\repositories\EmployeeRepository;
use PostApi\modules\HR\domain\entities\Salery;

class CreateSaleryAction
{
    public static function execute(array $body)
    {        
        $employeeId = $body['employee_id'] ?? null;
        $saleryValue = (float)($body['salery'] ?? 0);

        $employeeRepo = new EmployeeRepository();
        $employee = $employeeRepo->findOne($employeeId);

        $entity = new Salery(null, $employee, $saleryValue);
        $repo = new SaleryRepository();
        $repo->create($entity);
        return $entity;
    }
}
