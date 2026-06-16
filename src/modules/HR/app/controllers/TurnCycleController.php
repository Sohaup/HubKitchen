<?php

namespace PostApi\modules\HR\app\controllers;

use Error;
use PostApi\modules\HR\domain\services\turnCycle\CreateTurnCycleAction;
use PostApi\modules\HR\domain\services\turnCycle\DeleteTurnCycleAction;
use PostApi\modules\HR\domain\services\turnCycle\GetTurnCycleCollectionAction;
use PostApi\modules\HR\domain\services\turnCycle\GetTurnCycleItemAction;
use PostApi\modules\HR\domain\services\turnCycle\UpdateTurnCycleAction;
use PostApi\shared\app\controllers\api\ApiControllerContract;
use PostApi\shared\app\http\requests\Request;
use PostApi\shared\app\http\responses\success\json\Json;
use PostApi\shared\helpers\fecade\Chache;
use PostApi\shared\helpers\fecade\ViewError;

class TurnCycleController implements ApiControllerContract
{
    public function index()
    {
        $serin = GetTurnCycleCollectionAction::execute();
        return Chache::checkCache($serin);
    }

    public function get(string $id)
    {
        try {
            $serin = GetTurnCycleItemAction::execute((int)$id);
            http_response_code(200);
            return Json::toJson($serin);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "display turn cycle error", title: "incorrect parameter", status: true, detail: "no turn cycle for this id", statusCode: 400);
        }
    }

    public function create()
    {
        $request = new Request();
        $params = $request->body;
        if (!isset($params['employee_id'], $params['start_at'])) {
            return ViewError::viewProplem("creating turn cycle error", "missing required paramters error", 1, "missing required paramters employee_id, start_at  ", 400);
        }        
        try {
            $entity = CreateTurnCycleAction::execute();
            $serin = GetTurnCycleItemAction::execute((int)$entity->getId());
            http_response_code(201);
            return Json::toJson($serin);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "create turn cycle error", title: "incorrect parameter", status: true, detail: "unexpected error creating turn cycle", statusCode: 400);
        }
    }

    public function update(string $id)
    {
        $request = new Request();
        $params = $request->body;
        if (!isset($params['employee_id'], $params['start_at'])) {
            return ViewError::viewProplem("updating turn cycle error", "missing required paramters error", 1, "missing required paramters employee_id, start_at  ", 400);
        }
        try {
            UpdateTurnCycleAction::execute((int)$id);
            http_response_code(200);
            return Json::toJson(['message' => 'update turn cycle successfuly']);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "update turn cycle error", title: "incorrect parameter", status: true, detail: "there is no corresponding turn cycle for this id", statusCode: 400);
        }
    }

    public function delete(string $id)
    {
        try {
            DeleteTurnCycleAction::execute((int)$id);
            http_response_code(200);
            return Json::toJson(['message' => 'delete turn cycle successfuly']);
        } catch (Error $error) {
            return ViewError::viewProplem(type: "delete turn cycle error", title: "incorrect parameter", status: true, detail: "there is no corresponding turn cycle for this id", statusCode: 400);
        }
    }
}
