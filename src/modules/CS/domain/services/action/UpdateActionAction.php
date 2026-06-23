<?php

namespace PostApi\modules\CS\domain\services\action;

use PostApi\modules\CS\app\DB\repositories\ActionRepository;
use PostApi\modules\CS\domain\entities\Action;
use PostApi\shared\app\http\requests\Request;

class UpdateActionAction
{
    public static function execute(string $id)
    {
        $request = new Request();
        $params = $request->body;
        $repo = new ActionRepository();
        $entity = new Action();
        $entity->setId($id);
        $entity->setAction($params['action']);      
        $repo->update($entity);
    }
}
