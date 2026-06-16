<?php

namespace PostApi\modules\HR\domain\services\appraiselResult;

use PostApi\modules\HR\app\DB\repositories\AppraiselResultRepository;
use PostApi\modules\HR\app\DB\repositories\ApplicationCycleRepository;
use PostApi\modules\HR\app\DB\repositories\EvolutionCritiriaRepository;
use PostApi\modules\HR\app\DB\repositories\EmployeeRepository;
use PostApi\modules\HR\domain\entities\AppraiselResult;
use PostApi\shared\app\http\requests\Request;

class CreateAppraiselResultAction
{
    public static function execute()
    {
        $request = new Request();
        $body = $request->body;

        $cycleId = $body['cycle_id'] ?? null;
        $critiriaId = $body['critiria_id'] ?? null;
        $employeeId = $body['employee_id'] ?? null;
        $score = isset($body['score']) ? (float)$body['score'] : 0.0;
        $managerComments = $body['manager_comments'] ?? '';

        $cycleRepo = new ApplicationCycleRepository();
        $critiriaRepo = new EvolutionCritiriaRepository();
        $employeeRepo = new EmployeeRepository();

        $cycle = $cycleRepo->findOne((int)$cycleId);
        $critiria = $critiriaRepo->findOne((int)$critiriaId);
        $employee = $employeeRepo->findOne((string)$employeeId);

        $entity = new AppraiselResult(null, $cycle, $critiria, $employee, $score, $managerComments);

        $repo = new AppraiselResultRepository();
        $repo->create($entity);
        return $entity;
    }
}
