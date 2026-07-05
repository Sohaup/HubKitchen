<?php

namespace PostApi\modules\manegers\domain\services\plan;

use PostApi\modules\manegers\app\DB\repositories\PlanRepository;

class DeletePlanAction
{
    public static function execute(int $id)
    {
        $repo = new PlanRepository();
        $repo->delete($id);
    }
}
