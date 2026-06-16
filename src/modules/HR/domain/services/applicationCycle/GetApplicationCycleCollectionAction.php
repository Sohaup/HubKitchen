<?php

namespace PostApi\modules\HR\domain\services\applicationCycle;

use PostApi\modules\HR\app\DB\repositories\ApplicationCycleRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetApplicationCycleCollectionAction
{
    public static function execute()
    {
        $repo = new ApplicationCycleRepository();
        $items = $repo->findAll();
        $serin = SerializeToSerin::serializeCollection($items);
        return $serin;
    }
}
