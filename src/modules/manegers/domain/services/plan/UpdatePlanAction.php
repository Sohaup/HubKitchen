<?php

namespace PostApi\modules\manegers\domain\services\plan;

use PostApi\modules\manegers\app\DB\repositories\ManegerRepository;
use PostApi\modules\manegers\app\DB\repositories\PlanRepository;
use PostApi\shared\app\http\requests\Request;

class UpdatePlanAction
{
    public static function execute(int $id)
    {
        $request = new Request();
        $params = $request->body;
        $repo = new PlanRepository();
        $plan = $repo->findOne($id);
        if (!$plan) {
            throw new \Exception("plan not found");
        }
        if (isset($params['type'])) {
            $plan->setType($params['type']);
        }
        if (isset($params['name'])) {
            $plan->setName($params['name']);
        }
        if (isset($params['description'])) {
            $plan->setDescription($params['description']);
        }
        if (isset($params['maneger_id'])) {
            $manegerRepo = new ManegerRepository();
            $maneger = $manegerRepo->findOne($params['maneger_id']);
            $plan->setManeger($maneger);
        }
        $repo->update($plan);
        return $plan;
    }
}
