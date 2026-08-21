<?php

namespace PostApi\modules\manegers\domain\services\plan;

use PostApi\modules\manegers\app\DB\repositories\PlanRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetPlanCollectionAction
{
    public static function execute(array $items)
    {
        $serin = SerializeToSerin::serializeCollection($items);
        return $serin;
    }
}
