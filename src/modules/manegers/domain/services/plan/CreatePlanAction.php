<?php

namespace PostApi\modules\manegers\domain\services\plan;

use PostApi\modules\manegers\app\DB\repositories\PlanRepository;
use PostApi\modules\manegers\app\DB\repositories\ManegerRepository;
use PostApi\modules\manegers\domain\entities\Plan;
use PostApi\shared\app\http\requests\Request;

class CreatePlanAction
{
    public static function execute(): Plan
    {
        $request = new Request();
        $params = $request->body;
        $type = $params['type'] ?? '';        
        $name = $params['name'] ?? '';
        $description = $params['description'] ?? '';
        $manegerId = $params['maneger_id'] ?? null;
        $manegerRepo = new ManegerRepository();
        $maneger = $manegerRepo->findOne($manegerId);
        $plan = new Plan();
        $plan->setType($type);
        $plan->setName($name);
        $plan->setDescription($description);
        $plan->setManeger($maneger);
        $repo = new PlanRepository();
        $repo->create($plan);
        return $plan;
    }
}
