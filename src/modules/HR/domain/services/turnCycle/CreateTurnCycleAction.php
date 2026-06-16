<?php

namespace PostApi\modules\HR\domain\services\turnCycle;

use PostApi\modules\HR\app\DB\repositories\TurnCycleRepository;
use PostApi\modules\HR\app\DB\repositories\EmployeeRepository;
use PostApi\modules\HR\domain\entities\TurnCycle;
use PostApi\shared\app\http\requests\Request;

class CreateTurnCycleAction
{
    public static function execute()
    {
        $request = new Request();
        $body = $request->body;
        $start = $body['start_at'] ?? null;
        $leave = $body['leave_at'] ?? null;
        $employeeId = $body['employee_id'] ?? null;

        $employeeRepo = new EmployeeRepository();
        $employee = $employeeRepo->findOne($employeeId);

        $entity = new TurnCycle(null, $start, $leave, $employee);
        $repo = new TurnCycleRepository();
        $repo->create($entity);
        return $entity;
    }
}
