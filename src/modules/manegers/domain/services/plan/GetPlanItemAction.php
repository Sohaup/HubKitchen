<?php

namespace PostApi\modules\manegers\domain\services\plan;

use PostApi\modules\manegers\app\DB\repositories\PlanRepository;
use PostApi\shared\helpers\fecade\SerializeToSerin;

class GetPlanItemAction
{
    public static function execute(int $id)
    {
        $repo = new PlanRepository();
        $item = $repo->findOne($id);
        $serin = SerializeToSerin::serialize($item);
        return $serin;
    }
}
