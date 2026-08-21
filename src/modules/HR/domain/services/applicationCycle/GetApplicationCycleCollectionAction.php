<?php

namespace PostApi\modules\HR\domain\services\applicationCycle;

use PostApi\modules\HR\app\DB\repositories\ApplicationCycleRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetApplicationCycleCollectionAction
{
    public static function execute(array $items = null)
    {
        $repo = new ApplicationCycleRepository();
        $serin = SerializeToSerin::serializeCollection($items ?? $repo->findAll());
        return $serin;
    }
}
