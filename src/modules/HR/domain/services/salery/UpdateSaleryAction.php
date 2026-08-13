<?php

namespace PostApi\modules\HR\domain\services\salery;

use Error;
use PostApi\modules\HR\app\DB\repositories\SaleryRepository;
use PostApi\modules\HR\app\DB\repositories\EmployeeRepository;

class UpdateSaleryAction
{
    public static function execute(int $id , array $body)
    {
        $repo = new SaleryRepository();
        $entity = $repo->findOne($id);
        if (!$entity) {
            throw new Error('not found');
        }        
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
