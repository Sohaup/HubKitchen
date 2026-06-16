<?php

namespace PostApi\modules\HR\domain\services\turnCycle;

use Error;
use PostApi\modules\HR\app\DB\repositories\TurnCycleRepository;
use PostApi\modules\HR\app\DB\repositories\EmployeeRepository;
use PostApi\shared\app\http\requests\Request;

class UpdateTurnCycleAction
{
    public static function execute(int $id)
    {
        $repo = new TurnCycleRepository();
        $entity = $repo->findOne($id);
        if (!$entity) {
            throw new Error('not found');
        }
        $request = new Request();
        $body = $request->body;
        if (isset($body['start_at'])) {
            $entity->setStartAt($body['start_at']);
        }
        if (isset($body['leave_at'])) {
            $entity->setLeaveAt($body['leave_at']);
        }
        if (isset($body['employee_id'])) {
            $employeeRepo = new EmployeeRepository();
            $employee = $employeeRepo->findOne($body['employee_id']);
            $entity->setEmployee($employee);
        }
        $repo->update($entity);
    }
}
