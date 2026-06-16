<?php

namespace PostApi\modules\HR\domain\services\turnCycle;

use Error;
use PostApi\modules\HR\app\DB\repositories\TurnCycleRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetTurnCycleItemAction
{
    public static function execute(int $id)
    {
        $repo = new TurnCycleRepository();
        $entity = $repo->findOne($id);
        if (!$entity) {
            throw new Error('not found');
        }
        return SerializeToSerin::serialize($entity);
    }
}
