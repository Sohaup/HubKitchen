<?php

namespace PostApi\modules\CS\domain\services\status;

use PostApi\modules\CS\app\DB\repositories\StatusRepository;
use PostApi\modules\CS\domain\entities\Status;
use PostApi\shared\app\http\requests\Request;

class UpdateStatusAction
{
    public static function execute(string $id)
    {
        $request = new Request();
        $params = $request->body;
        $repo = new StatusRepository();
        $entity = new Status();
        $entity->setId($id);
        $entity->setStatus($params['status']);      
        $repo->update($entity);
    }
}
