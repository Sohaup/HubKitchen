<?php

namespace PostApi\modules\HR\domain\services\applicationCycle;

use Error;
use PostApi\modules\HR\app\DB\repositories\ApplicationCycleRepository;
use PostApi\modules\HR\helpers\types\AppraisalStatusType;
use PostApi\shared\app\http\requests\Request;

class UpdateApplicationCycleAction
{
    public static function execute(int $id)
    {
        $repo = new ApplicationCycleRepository();
        $entity = $repo->findOne($id);
        if (!$entity) {
            throw new Error('not found');
        }
        $request = new Request();
        $body = $request->body;
        if (isset($body['name'])) {
            $entity->setName($body['name']);
        }
        if (isset($body['starts_at'])) {
            $entity->setStartsAt($body['starts_at']);
        }
        if (isset($body['ends_at'])) {
            $entity->setEndsAt($body['ends_at']);
        }
        if (isset($body['status'])) {
            $entity->setStatus($body['status']);
        }
        $repo->update($entity);
    }
}
