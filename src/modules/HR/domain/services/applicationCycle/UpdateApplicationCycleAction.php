<?php

namespace PostApi\modules\HR\domain\services\applicationCycle;

use Error;
use PostApi\modules\HR\app\DB\repositories\ApplicationCycleRepository;

class UpdateApplicationCycleAction
{
    public static function execute(int $id , array $body)
    {
        $repo = new ApplicationCycleRepository();
        $entity = $repo->findOne($id);
        if (!$entity) {
            throw new Error('not found');
        }        
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
