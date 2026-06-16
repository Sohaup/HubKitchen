<?php

namespace PostApi\modules\HR\domain\services\applicationCycle;

use Error;
use PostApi\modules\HR\app\DB\repositories\ApplicationCycleRepository;

class DeleteApplicationCycleAction
{
    public static function execute(int $id)
    {
        $repo = new ApplicationCycleRepository();
        $entity = $repo->findOne($id);
        if (!$entity) {
            throw new Error('not found');
        }
        $repo->delete($id);
    }
}
