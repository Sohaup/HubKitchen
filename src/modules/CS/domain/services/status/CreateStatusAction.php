<?php

namespace PostApi\modules\CS\domain\services\status;

use PostApi\modules\CS\app\DB\repositories\StatusRepository;
use PostApi\modules\CS\domain\entities\Status;

class CreateStatusAction
{
    public static function execute(array $params): Status
    {       
        $repo = new StatusRepository();
        $entity = new Status();
        $entity->setStatus($params['status']);      
        $repo->create($entity);
        return $entity;
    }
}
