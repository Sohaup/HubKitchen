<?php

namespace PostApi\modules\HR\domain\services\turnCycle;

use PostApi\modules\HR\app\DB\repositories\TurnCycleRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetTurnCycleCollectionAction
{
    public static function execute()
    {
        $repo = new TurnCycleRepository();
        $items = $repo->findAll();
        $serin = SerializeToSerin::serializeCollection($items);
        return $serin;
    }
}
