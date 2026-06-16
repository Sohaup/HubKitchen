<?php

namespace PostApi\modules\HR\domain\services\salery;

use PostApi\modules\HR\app\DB\repositories\SaleryRepository;
use PostApi\modules\HR\app\DB\repositories\EmployeeRepository;
use PostApi\modules\HR\domain\entities\Salery;
use PostApi\shared\app\http\requests\Request;

class CreateSaleryAction
{
    public static function execute()
    {
        $request = new Request();
        $body = $request->body;
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
