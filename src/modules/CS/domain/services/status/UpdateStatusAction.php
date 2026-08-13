<?php

namespace PostApi\modules\CS\domain\services\status;

use PostApi\modules\CS\app\DB\repositories\StatusRepository;
use PostApi\modules\CS\domain\entities\Status;

class UpdateStatusAction
{
    public static function execute(string $id , array $params)
    {        
        $repo = new StatusRepository();
        $entity = new Status();
        $entity->setId($id);
        $entity->setStatus($params['status']);      
        $repo->update($entity);
    }
}
