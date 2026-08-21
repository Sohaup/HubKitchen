<?php

namespace PostApi\modules\manegers\app\controllers;

use Error;
use Exception;

use PostApi\modules\manegers\domain\services\plan\CreatePlanAction;
use PostApi\modules\manegers\domain\services\plan\UpdatePlanAction;
use PostApi\modules\manegers\domain\services\plan\DeletePlanAction;
use PostApi\modules\manegers\domain\services\plan\GetPlanCollectionAction;
use PostApi\modules\manegers\domain\services\plan\GetPlanItemAction;
use PostApi\shared\app\controllers\api\ApiControllerContract;
use PostApi\shared\app\http\requests\Request;
use PostApi\shared\app\http\responses\success\json\Json;
use PostApi\shared\helpers\fecade\Chache;
use PostApi\shared\helpers\fecade\ViewError;

class PlanController implements ApiControllerContract
{
    public function index(Request $request)
    {
        try {
            $body = $request->body;
            $planRepository = new \PostApi\modules\manegers\app\DB\repositories\PlanRepository();
            $critiria = [];

            if (isset($body['id'])) {
                $critiria['id'] = $body['id'];
            }
            if (isset($body['type'])) {
                $critiria['type'] = $body['type'];
            }
            if (isset($body['name'])) {
                $critiria['name'] = $body['name'];
            }
            if (isset($body['description'])) {
                $critiria['description'] = $body['description'];
            }
            if (isset($body['maneger_id'])) {
                $critiria['maneger_id'] = $body['maneger_id'];
            }

            if (!empty($critiria)) {
                $plans = $planRepository->findBy($critiria);
            } else {
                $plans = $planRepository->findAll();
            }

            $serin = GetPlanCollectionAction::execute($plans);
            http_response_code(200);
            return Chache::checkCache($serin);
        } catch (Exception $error) {
            return ViewError::viewProplem('fetch error', 'internal error', 1, $error->getMessage(), 500);
        }
    }

    public function create(Request $request)
    {        
        $body = $request->body;
        if (!isset($body['type'], $body['name'], $body['description'], $body['maneger_id'])) {
            return ViewError::viewProplem('create plan error', 'missing required paramters', 1, 'missing required paramters type, name, description, maneger_id', 400);
        }
        try {
            $plan = CreatePlanAction::execute($body);            
            $serin = GetPlanItemAction::execute($plan->getId());
            http_response_code(201);
            return Json::toJson($serin);
        } catch (Exception $error) {
            return ViewError::viewProplem('create error', 'internal error', 1, $error->getMessage(), 500);
        }
    }

    public function get(string $id)
    {
        try {
            $serin = GetPlanItemAction::execute($id);
            http_response_code(200);
            return Json::toJson($serin);
        } catch (Error $error) {
            return ViewError::viewProplem('fetch error', 'internal error', 1,"no plan for this id", 400);
        }
    }

    public function update(Request $request,string $id)
    {        
        $body = $request->body;
        if (!isset($body['type'], $body['name'], $body['description'], $body['maneger_id'])) {
            return ViewError::viewProplem('update plan error', 'missing required paramters', 1, 'missing required paramters type, name, description, maneger_id', 400);
        }
        try {
            UpdatePlanAction::execute($id , $body);
            http_response_code(200);
            return Json::toJson(['message' => 'plan updated successfuly']);
        } catch (Error $error) {
            return ViewError::viewProplem('update error', 'internal error', 1, "no plan for this id", 400);
        }
    }

    public function delete(string $id)
    {
        try {
            DeletePlanAction::execute($id);
            http_response_code(200);
            return Json::toJson(['message' => 'plan deleted']);
        } catch (Error $error) {
            return ViewError::viewProplem('delete error', 'internal error', 1, "no plan for this id", 400);
        }
    }
}
