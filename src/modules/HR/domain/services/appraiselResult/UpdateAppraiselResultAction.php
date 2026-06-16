<?php

namespace PostApi\modules\HR\domain\services\appraiselResult;

use Error;
use PostApi\modules\HR\app\DB\repositories\AppraiselResultRepository;
use PostApi\modules\HR\app\DB\repositories\ApplicationCycleRepository;
use PostApi\modules\HR\app\DB\repositories\EvolutionCritiriaRepository;
use PostApi\modules\HR\app\DB\repositories\EmployeeRepository;
use PostApi\shared\app\http\requests\Request;

class UpdateAppraiselResultAction
{
    public static function execute(int $id)
    {
        $repo = new AppraiselResultRepository();
        $entity = $repo->findOne($id);
        if (!$entity) {
            throw new Error('not found');
        }

        $request = new Request();
        $body = $request->body;

        if (isset($body['cycle_id'])) {
            $cycleRepo = new ApplicationCycleRepository();
            $entity->setCycle($cycleRepo->findOne((int)$body['cycle_id']));
        }
        if (isset($body['critiria_id'])) {
            $critiriaRepo = new EvolutionCritiriaRepository();
            $entity->setCritiria($critiriaRepo->findOne((int)$body['critiria_id']));
        }
        if (isset($body['employee_id'])) {
            $employeeRepo = new EmployeeRepository();
            $entity->setEmployee($employeeRepo->findOne((string)$body['employee_id']));
        }
        if (isset($body['score'])) {
            $entity->setScore((float)$body['score']);
        }
        if (isset($body['manager_comments'])) {
            $entity->setManagerComments($body['manager_comments']);
        }

        $repo->update($entity);
    }
}
