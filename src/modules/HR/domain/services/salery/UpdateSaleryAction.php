<?php

namespace PostApi\modules\HR\domain\services\salery;

use Error;
use PostApi\modules\HR\app\DB\repositories\SaleryRepository;
use PostApi\modules\HR\app\DB\repositories\EmployeeRepository;
use PostApi\shared\app\http\requests\Request;

class UpdateSaleryAction
{
    public static function execute(int $id)
    {
        $repo = new SaleryRepository();
        $entity = $repo->findOne($id);
        if (!$entity) {
            throw new Error('not found');
        }
        $request = new Request();
        $body = $request->body;
        if (isset($body['employee_id'])) {
            $employeeRepo = new EmployeeRepository();
            $employee = $employeeRepo->findOne($body['employee_id']);
            $entity->setEmployee($employee);
        }
        if (isset($body['salery'])) {
            $entity->setSalery((float)$body['salery']);
        }
        $repo->update($entity);
    }
}
