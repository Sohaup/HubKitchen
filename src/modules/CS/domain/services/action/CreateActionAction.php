<?php

namespace PostApi\modules\CS\domain\services\action;

use PostApi\modules\CS\app\DB\repositories\ActionRepository;
use PostApi\modules\CS\domain\entities\Action;

class CreateActionAction
{
    public static function execute(array $params): Action
    {        
        $repo = new ActionRepository();
        $entity = new Action();
        $entity->setAction($params['action']);       
        $repo->create($entity);
        return $entity;
    }
}
