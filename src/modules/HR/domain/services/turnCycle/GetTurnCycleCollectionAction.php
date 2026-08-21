<?php

namespace PostApi\modules\HR\domain\services\turnCycle;

use PostApi\modules\HR\app\DB\repositories\TurnCycleRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetTurnCycleCollectionAction
{
    public static function execute(array $items = null)
    {
        $repo = new TurnCycleRepository();
        $serin = SerializeToSerin::serializeCollection($items ?? $repo->findAll());
        return $serin;
     
    }
}
