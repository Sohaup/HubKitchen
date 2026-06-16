<?php

namespace PostApi\modules\HR\domain\services\payroll;

use Error;
use PostApi\modules\HR\app\DB\repositories\PayrollRepository;
use PostApi\modules\HR\app\DB\repositories\EmployeeRepository;
use PostApi\modules\HR\app\DB\repositories\SaleryComponentRepository;
use PostApi\shared\app\http\requests\Request;

class UpdatePayrollAction
{
    public static function execute(int $id)
    {
        $repo = new PayrollRepository();
        $entity = $repo->findOne($id);
        if (!$entity) {
            throw new Error('not found');
        }
        $request = new Request();
        $body = $request->body;
        if (isset($body['employee_id'])) {
            $employeeRepo = new EmployeeRepository();
            $entity->setEmployee($employeeRepo->findOne($body['employee_id']));
        }
        if (isset($body['salery_component_id'])) {
            $componentRepo = new SaleryComponentRepository();
            $entity->setSaleryComponent($componentRepo->findOne($body['salery_component_id']));
        }
        if (isset($body['amount'])) {
            $entity->setAmount((float)$body['amount']);
        }
        if (isset($body['date'])) {
            $entity->setDate($body['date']);
        }
        $repo->update($entity);
    }
}
