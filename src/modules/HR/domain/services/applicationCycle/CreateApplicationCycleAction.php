<?php

namespace PostApi\modules\HR\domain\services\applicationCycle;

use PostApi\modules\HR\app\DB\repositories\ApplicationCycleRepository;
use PostApi\modules\HR\domain\entities\ApplicationCycle;
use PostApi\modules\HR\app\DB\repositories\ApplicationCycleRepository as Repo;
use PostApi\modules\HR\helpers\types\AppraisalStatusType;
use PostApi\shared\app\http\requests\Request;

class CreateApplicationCycleAction
{
    public static function execute()
    {
        $request = new Request();
        $body = $request->body;
        $name = $body['name'] ?? '';
        $starts = $body['starts_at'] ?? '';
        $ends = $body['ends_at'] ?? '';
        $statusRaw = $body['status'] ?? AppraisalStatusType::DRAFTED->value;
        $status = AppraisalStatusType::from($statusRaw);

        $entity = new ApplicationCycle(null, $name, $starts, $ends, $status->value);
        $repo = new ApplicationCycleRepository();
        $repo->create($entity);
        return $entity;
    }
}
