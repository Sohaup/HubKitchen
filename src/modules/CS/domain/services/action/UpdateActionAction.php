<?php

namespace PostApi\modules\CS\domain\services\action;

use PostApi\modules\CS\app\DB\repositories\ActionRepository;
use PostApi\modules\CS\domain\entities\Action;

class UpdateActionAction
{
    public static function execute(string $id , array $params)
    {        
        $repo = new ActionRepository();
        $entity = new Action();
        $entity->setId($id);
        $entity->setAction($params['action']);      
        $repo->update($entity);
    }
}
